<?php
namespace Application\Lib\Tools\Uploader\Bilder;
/**
 * @author alexander
 */
interface FileUploadInterface {
    public function save($savePath);
    public function getFileName();
    public function getFileSize();
}
