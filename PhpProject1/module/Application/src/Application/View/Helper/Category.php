<?php
namespace Application\View\Helper;

use Application\View\Helper\CoreAbstractHelper;

class Category extends CoreAbstractHelper{
    
    /**
	* return categories
	* 
	* @return \Model\Entity\ProductType[]
	*/
    public function __invoke() {
		$em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $categoryList = $em->getRepository('Model\Entity\ProductType')->findByStatus(true);
		return $categoryList;
	}
}
