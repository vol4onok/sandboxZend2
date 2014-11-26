<?php

namespace Application\View\Helper;
use Application\Lib\Tools\Image\SimpleImage;
use Application\View\Helper\CoreAbstractHelper;
class ResizeProductHelper extends CoreAbstractHelper {
    
    protected $pathToPublic; // path to public dir
    protected $pathToImage; // path to current image
    protected $pathToNewImage; // path to new resize image
    protected $pathToSaveImage;
    protected $imageExt;
    protected $width;
    protected $hight;
    /**
     * @var \Model\Cache\RedisCache
     */
    protected $redis;


    
    public function __construct() {
        $this->pathToPublic = PUBLIC_UPLOAD;
    }
    
    public function __invoke($imagePath, $width = 100, $hight = 100, $ext = "gif") {
        $this->redis = $this->getServiceLocator()->get('Redis');
		//set param
        $this->setPathToImage($imagePath);
        $this->imageExt = $ext;
        $this->hight = $hight;
        $this->width = $width;
        $this->setPathToSaveImage();

        $pathToConvertImage = $this->getFullPathToImage();

        if (!file_exists($pathToConvertImage) || !$imagePath) {
            return $this->getView()->basePath('images/logo.png');
        }
        if (!file_exists($this->redis->get($this->getPathToSaveImage()))) {
            $simpleImage = new SimpleImage($pathToConvertImage, $width, $hight);
            $simpleImage->best_fit($width, $hight);
            $simpleImage->save($this->getFullPathToSaveImage());
            $this->redis->set($this->getPathToSaveImage(), $this->getFullPathToSaveImage());
        }
        $pathToImage = $this->getView()->basePath($this->getPathToSaveImage());
		return $pathToImage;
	}
    
    private function setPathToImage($imagePath)
    {
        $this->pathToImage = $imagePath;
    }
    
    private function getPathToImage()
    {
        return $this->pathToImage;
    }
    private function getFullPathToImage()
    {
        $fullPath = ($this->pathToPublic . $this->pathToImage);
        return $fullPath;
    }
    
    private function setPathToSaveImage()
    {
        $pathToImage = $this->getPathToImage();
        $imagePath = preg_replace('/\.[^\.\/]+$/', '', $this->getPathToImage());
        $this->pathToSaveImage = $imagePath . '-' .$this->width . 'x' . $this->hight . '.' . $this->imageExt;
    }
    
    private function getPathToSaveImage()
    {
        return $this->pathToSaveImage;
    }
    
    private function getFullPathToSaveImage()
    {
        $fullPath = ($this->pathToPublic . $this->pathToSaveImage);
        return $fullPath;
    }
}
