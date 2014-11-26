<?php

namespace Admin\Controller;

use Application\Lib\AppController;
use Zend\View\Model\JsonModel;

/**
 * Description of UploaderController
 *
 * @author alexander
 */
class UploaderController extends AppController {

    const PRODUCT_DIR = 'uploads/products/';

    public function indexAction() {
        $upload_dir = PUBLIC_UPLOAD . self::PRODUCT_DIR;
        $valid_extensions = array('gif', 'png', 'jpeg', 'jpg');
        /**
         * @var \Application\Lib\Tools\Uploader\FileUpload
         */
        $upload = $this->getServiceLocator()->get('Application\Lib\Tools\Uploader\FileUpload');
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755);
        }
        $upload->setFileName($upload->getFileName() . $this->generate_password(6));
        $result = $upload->handleUpload($upload_dir, $valid_extensions);
        if (!$result) {
            return new JsonModel(array('success' => false, 'msg' => $upload->getErrorMsg()));
        } else {
            $attachData = array('resource' => self::PRODUCT_DIR . $upload->getFileFullName());
            $id = $this->getServiceLocator()->get('AttachmentTable')->insert($attachData);
            return new JsonModel(array('success' => true, 'file' => $upload->getFileFullName(), 'id' => $id));
        }
    }

    public function uploadProgressAction() {
        $server = $this->getServiceLocator()->get('Request')->getServer();

        if (isset($server['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$server['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }

        if (isset($_REQUEST['progresskey']))
            $status = apc_fetch('upload_' . $_REQUEST['progresskey']);
        else
            return new JsonModel(array('success' => false));

        $pct = 0;
        $size = 0;

        if (is_array($status)) {
            if (array_key_exists('total', $status) && array_key_exists('current', $status)) {
                if ($status['total'] > 0) {
                    $pct = round(( $status['current'] / $status['total']) * 100);
                    $size = round($status['total'] / 1024);
                }
            }
        }

        return new JsonModel(array('success' => true, 'pct' => $pct, 'size' => $size));
    }

    private function cors() {
        $server = $this->getServiceLocator()->get('Request')->getServer();
        if (isset($server['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$server['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }

        // Access-Control headers are received during OPTIONS requests
        if (isset($server['REQUEST_METHOD'])) {
            if ($server['REQUEST_METHOD'] == 'OPTIONS') {
                if (isset($server['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
                if (isset($server['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                    header("Access-Control-Allow-Headers: {$server['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
                exit;
            }
        }
    }

    public function deleteAction() {
        $imageId = (int) $this->getRequest()->getPost('id');
        if ($imageId) {
            $image = $this->getServiceLocator()->get('AttachmentTable')->findOne(array('id' => $imageId));
            if ($image->count() > 0) {
                $resourceImage = $image->current()->resource;
                if (file_exists(PUBLIC_UPLOAD . $resourceImage)) {
                    $imagePath = preg_replace('/\.[^\.\/]+$/', '', $resourceImage);
                    $imagePaths = glob(PUBLIC_UPLOAD . $imagePath . '*');
                    $removeFiles = array_map('unlink', glob(PUBLIC_UPLOAD . $imagePath . '*'));
                    if (in_array(true, $removeFiles)) {
                        $this->getServiceLocator()->get('AttachmentTable')->delete(array('id' => $imageId));
                    } else {
                        return new JsonModel(array(
                            'msg' => 'Upload directory is not writable',
                            'success' => false,
                            'id' => $imageId,
                        ));
                    }
                } else {
                    $this->getServiceLocator()->get('AttachmentTable')->delete(array('id' => $imageId));
                }

                return new JsonModel(array(
                    'id' => $imageId,
                    'success' => true,
                ));
            }
            return new JsonModel(array(
                'msg' => 'File not found.',
                'success' => false,
            ));
        }
        return new JsonModel(array(
            'msg' => 'Query Error',
            'success' => false,
        ));
    }

    private function generate_password($number) {
        $arr = array('a', 'b', 'c', 'd', 'e', 'f',
            'g', 'h', 'i', 'j', 'k', 'l',
            'm', 'n', 'o', 'p', 'r', 's',
            't', 'u', 'v', 'x', 'y', 'z',
            'A', 'B', 'C', 'D', 'E', 'F',
            'G', 'H', 'I', 'J', 'K', 'L',
            'M', 'N', 'O', 'P', 'R', 'S',
            'T', 'U', 'V', 'X', 'Y', 'Z',
            '1', '2', '3', '4', '5', '6',
            '7', '8', '9', '0');
        // Генерируем пароль
        $pass = "";
        for ($i = 0; $i < $number; $i++) {
            // Вычисляем случайный индекс массива
            $index = rand(0, count($arr) - 1);
            $pass .= $arr[$index];
        }
        return $pass;
    }

}
