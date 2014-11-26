<?php

namespace Application\Lib\Tools\Uploader;
use Application\Lib\Tools\Uploader\Bilder\FileUploadInterface;
/**
 * Main class for handling file uploads
 */
class FileUpload {

    public $uploadDir;                    // File upload directory (include trailing slash)
    public $allowedExtensions;            // Array of permitted file extensions
    public $sizeLimit = 10485760;         // Max file upload size in bytes (default 10MB)
    public $corsInputName = 'XHR_CORS_TARGETORIGIN';
    private $fileName;                    // Filename of the uploaded file
    private $fileSize;                    // Size of uploaded file in bytes
    private $fileExtension;               // File extension of uploaded file
    private $savedFile;                   // Path to newly uploaded file (after upload completed)
    private $errorMsg;                    // Error message if handleUpload() returns false (use getErrorMsg() to retrieve)
    private $handler;
    private $sm;

    public function __construct(\Zend\ServiceManager\ServiceManager $sm) {

        $uploadName = 'imageType';
        $this->sm = $sm;
        
        $files = $this->sm->get('request')->getFiles()->toArray();
        $get = $this->sm->get('request')->getQuery()->toArray();

        if (isset($files[$uploadName])) {
            $handler = $this->sm->get('Application\Lib\Tools\Uploader\Bilder\FileUploadPOSTForm'); // Form-based upload
        } elseif (isset($get[$uploadName])) {
            $handler = $this->sm->get('Application\Lib\Tools\Uploader\Bilder\FileUploadXHR'); // XHR upload
        } else {
            $handler = false;
        }
        $this->handler = $handler;
        if ($this->handler instanceof FileUploadInterface) {
            $this->handler->uploadName = $uploadName;
            $this->fileSize = $this->handler->getFileSize();
            $fileInfo = pathinfo($this->handler->getFileName());
            if (array_key_exists('extension', $fileInfo)) {
                $this->fileName = strtolower($fileInfo['filename']);
                $this->fileExtension = strtolower($fileInfo['extension']);
            }
        }
    }

    public function setFileName($newFileName) {
        $this->fileName = $newFileName;
    }
    
    public function getFileFullName() {
        
        $filePath = $this->fileName . '.' . $this->fileExtension;
        return $filePath;
    }
    
    public function getFileName() {
        return $this->fileName;
    }

    public function getFileSize() {
        return $this->fileSize;
    }

    public function getExtension() {
        return $this->fileExtension;
    }

    public function getErrorMsg() {
        return $this->errorMsg;
    }

    public function getSavedFile() {
        return $this->savedFile;
    }

    private function checkExtension($ext, $allowedExtensions) {
        if (!is_array($allowedExtensions)) {
            return false;
        }
        if (!in_array(strtolower($ext), array_map('strtolower', $allowedExtensions))) {
            return false;
        }
        return true;
    }

    private function setErrorMsg($msg) {
        $this->errorMsg = $msg;
    }

    private function fixDir($dir) {
        $last = substr($dir, -1);
        if ($last == '/' || $last == '\\') {
            $dir = substr($dir, 0, -1);
        }
        return $dir . DIRECTORY_SEPARATOR;
    }

    // escapeJS and jsMatcher are adapted from the Escaper component of 
    // Zend Framework, Copyright (c) 2005-2013, Zend Technologies USA, Inc.
    // https://github.com/zendframework/zf2/tree/master/library/Zend/Escaper
    private function escapeJS($string) {
        return preg_replace_callback('/[^a-z0-9,\._]/iSu', $this->jsMatcher, $string);
    }

    private function jsMatcher($matches) {
        $chr = $matches[0];
        if (strlen($chr) == 1) {
            return sprintf('\\x%02X', ord($chr));
        }
        if (function_exists('iconv')) {
            $chr = iconv('UTF-16BE', 'UTF-8', $chr);
        } elseif (function_exists('mb_convert_encoding')) {
            $chr = mb_convert_encoding($chr, 'UTF-8', 'UTF-16BE');
        }
        return sprintf('\\u%04s', strtoupper(bin2hex($chr)));
    }

    public function corsResponse($data) {
        if (isset($_REQUEST[$this->corsInputName])) {
            $targetOrigin = $this->escapeJS($_REQUEST[$this->corsInputName]);
            $targetOrigin = htmlspecialchars($targetOrigin, ENT_QUOTES, 'UTF-8');
            return "<script>window.parent.postMessage('$data','$targetOrigin');</script>";
        }
        return $data;
    }

    public function handleUpload($uploadDir = null, $allowedExtensions = null) {
        if (!$this->handler instanceof FileUploadInterface) {
            $this->setErrorMsg('Incorrect upload name or no file uploaded');
            return false;
        }

        if (!empty($uploadDir)) {
            $this->uploadDir = $uploadDir;
        }
        if (is_array($allowedExtensions)) {
            $this->allowedExtensions = $allowedExtensions;
        }

        $this->uploadDir = $this->fixDir($this->uploadDir);

        $this->savedFile = $this->uploadDir . $this->getFileFullName();

        if ($this->fileSize == 0) {
            $this->setErrorMsg('File is empty');
            return false;
        }
        if (!is_writable($this->uploadDir)) {
            $this->setErrorMsg('Upload directory is not writable');
            return false;
        }
        if ($this->fileSize > $this->sizeLimit) {
            $this->setErrorMsg('File size exceeds limit');
            return false;
        }
        if (!empty($this->allowedExtensions)) {
            if (!$this->checkExtension($this->fileExtension, $this->allowedExtensions)) {
                $this->setErrorMsg('Invalid file type');
                return false;
            }
        }
        if (!$this->handler->save($this->savedFile)) {
            $this->setErrorMsg('File could not be saved');
            return false;
        }

        return true;
    }

}
