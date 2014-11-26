<?php
namespace Application\Lib\Tools\Uploader\Bilder;
use Application\Lib\Tools\Uploader\Bilder\FileUploadInterface;
/**
 * Description of FileUploadPOSTForm
 *
 * @author alexander
 */
class FileUploadPOSTForm implements FileUploadInterface
{
  public $uploadName;
  private $sm;
  private $filesVar = array();


  public function __construct(\Zend\ServiceManager\ServiceManager $sm) {
      $this->sm = $sm;
      $this->filesVar = $this->sm->get('request')->getFiles()->toArray();
  }

  public function save($savePath) {
    if (move_uploaded_file($this->filesVar[$this->uploadName]['tmp_name'], $savePath)) {
      return true;
    }
    return false;
  }
  
  public function getFileName() {
    return $this->filesVar[$this->uploadName]['name'];
  }
  
  public function getFileSize() {
    return $this->filesVar[$this->uploadName]['size'];
  }
}