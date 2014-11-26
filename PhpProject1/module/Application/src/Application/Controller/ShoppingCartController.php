<?php

namespace Application\Controller;
use Application\Lib\AppController;
use Application\Form\ProductActionForm;
use Application\Form\EditOrderForm;

class ShoppingCartController extends AppController {

    /*
     * @var \Application\Model\ShoppingCartTable
     */
    protected $shoppingCartTable;
    
    public function ready() {
        parent::ready();
        $this->shoppingCartTable = $this->getServiceLocator()->get('shoppingCartTable');
    }

    public function indexAction() {

        $auth = $this->getServiceLocator()->get('Auth');
        $sessionProductShoppingCart = array();
        if ($auth->hasIdentity()) {
            $userTable = $this->getServiceLocator()->get('UserTable');
            $list = $userTable->getShoppingCartProductListFromUser($this->user);
        } else {
            $sessionProductShoppingCart = $this->shoppingCartTable->getSessionProductList();
            if (empty($sessionProductShoppingCart)) {
                return array(
                    'list' => array(),
                );
            }
            $productIds = array_keys($sessionProductShoppingCart);
            $productTable = $this->getServiceLocator()->get('ProductTable');
            $params = array($productTable->getTable() . '.id' => $productIds);
            $list = $productTable->getProductList($params);
        }
        $form = new ProductActionForm();
        return array(
            'sessionProductShoppingCart'  => $sessionProductShoppingCart,
            'list' => $list,
            'form' => $form,
        );
    }
    
    public function addShoppingCartAction() {
        $slugPage = $this->params()->fromRoute('slug');
        $id = $this->request->getPost('id', 0);
        $params = array('slug' => $slugPage, 'product.id' => $id);
        $productTable = $this->getServiceLocator()->get('ProductTable');
        $product = $productTable->getProductList($params);
        if ($product->count() == 0) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        $productId = $product->current()->id;
        $shoppingCartModel = $this->getServiceLocator()->get('ShoppingCartTable');
        /**
         * @var $auth Zend\Authentication\AuthenticationService
         */
        $auth = $this->getServiceLocator()->get('Auth');
        if ($auth->hasIdentity()) {
            $shoppingCartModel->addProduct($this->user->getUser()->getId(), $productId);
         } else {
            $shoppingCartModel->addSessoinProduct($productId);
        }
        return $this->redirect()->toRoute('shopping-cart');
    }

    public function deleteShoppingCartProductAction()
    {
        $slugPage = $this->params()->fromRoute('slug');
        $id = $this->request->getPost('id', 0);
        $params = array('slug' => $slugPage, 'product.id' => $id);
        $productTable = $this->getServiceLocator()->get('ProductTable');
        $product = $productTable->getProductList($params);
        if ($product->count() == 0) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        $shoppingCartModel = $this->getServiceLocator()->get('ShoppingCartTable');
        /**
         * @var $auth Zend\Authentication\AuthenticationService
         */
        $auth = $this->getServiceLocator()->get('Auth');
        if ($auth->hasIdentity()) {
            $shoppingCartModel->deleteByProductId($this->user->getUser()->getId(), $product->current()->id);
        } else {
            $shoppingCartModel->deleteSessionProductById($product->current()->id);
        }
        return $this->redirect()->toRoute('shopping-cart');
    }
    
    public function editShoppingCartProductAction()
    {
        $slugPage = $this->params()->fromRoute('slug');
        $id = $this->request->getPost('id', 0);
        $params = array('slug' => $slugPage, 'product.id' => $id);
        $productTable = $this->getServiceLocator()->get('ProductTable');
        $product = $productTable->getProductList($params);
        if ($product->count() == 0) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        $form = new EditOrderForm();
        $product = $product->current();
        $form->setData($product);
        if ($this->request->isPost()) {
            $data = $this->request->getPost()->toArray();
            $form->setData($data);
            if(isset($data['saveProduct'])) {
                $form->setInputFilter($form->getProductFilter());
				if ($form->isValid()) {
                    $fotmData = (array) $form->getData();
                    $insertData = array('id_user' => $this->userId, 'id_product' => $product->id);
                    $this->shoppingCartTable->insertOrUpdate($insertData, array('count' => $fotmData['count']));
                    return $this->redirect()->toRoute('shopping-cart');
                }
            }
        }
        return array(
            'product' => $product,
            'form' => $form,
        );
    }
}
