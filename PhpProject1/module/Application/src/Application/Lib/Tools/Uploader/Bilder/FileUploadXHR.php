<?php
namespace Application\Lib\Tools\Uploader\Bilder;

use Application\Lib\Tools\Uploader\Bilder\FileUploaInterface;

/**
 * Description of FileUploadXHR
 *
 * @author alexander
 */
class FileUploadXHR  implements FileUploadInterface
{
  public $uploadName;
  private $sm;


  public function __construct(\Zend\ServiceManager\ServiceManager $sm) {
      $this->sm = $sm;
  }
  
  public function save($savePath) {
    if (false !== file_put_contents($savePath, fopen('php://input', 'r'))) {
      return true;
    }
    return false;
  }
  
  public function getFileName() {
    $getVar = $this->sm->get('request')->getQuery()->toArray();
    return $getVar[$this->uploadName];
  }
  
  public function getFileSize() {
    $serverVar = $this->sm->get('request')->getServer();
    if (isset( $serverVar['CONTENT_LENGTH'])) {
      return (int) $serverVar['CONTENT_LENGTH'];
    } else {
      throw new \Exception('Content length not supported.');
    }
  }
}