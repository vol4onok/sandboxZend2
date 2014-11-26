<?php
namespace Admin\Controller;
use Grid\ScaffoldAbstractController;

class CategoryController extends ScaffoldAbstractController {
	
    public function ready() {
	parent::ready();
        $this->setForm($this->getServiceLocator()->get('Admin\Form\CategoryForm'));
	$this->_entityName = 'Model\Entity\ProductType';
    }
}
