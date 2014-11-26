<?php
namespace Application\View\Helper;

use Application\View\Helper\CoreAbstractHelper;

class ShoppingCartHelper extends CoreAbstractHelper{
	
    protected $user;
	
    public function __invoke() {
        $this->user = $this->getServiceLocator()->get('User')->getUser();
        $productSessionList = array();
        $productSessionList = $this->getServiceLocator()->get('ShoppingCartTable')->getSessionProductList();
        if (!empty($productSessionList) && is_array($productSessionList) && $this->user->getId()) {
            $this->getServiceLocator()->get('ShoppingCartTable')->addProducts($this->user->getId(), $productSessionList);
            $this->getServiceLocator()->get('ShoppingCartTable')->unsetSessionProductList();
        }
        return $this;
	}
    
    public function getCountShoppingCartProduct() {
        if ($this->user->getId())
        {
            $user = $this->getServiceLocator()->get('User')->getUser();
            return count($this->getServiceLocator()->get('doctrine.entitymanager.orm_default')->getRepository('Model\Entity\ShoppingCart')->findByIdUser($this->user));
            
        } else {
            return count($this->getServiceLocator()->get('ShoppingCartTable')->getSessionProductList());
        }
	}
}
