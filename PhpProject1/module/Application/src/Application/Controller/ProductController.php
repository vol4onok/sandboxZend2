<?php

namespace Application\Controller;
use Application\Lib\AppController;
use Application\Form\ProductActionForm;

class ProductController extends AppController {

    protected $productTable;

    const COUNT_PER_PAGE = 12;

    public function ready() {
        parent::ready();
        $this->productTable = $this->getServiceLocator()->get('ProductTable');
        
    }

    public function indexAction() {
        $slugPage = $this->params()->fromRoute('slug');
        $product = $this->productTable->getProductBySlug($slugPage);
        if (empty($product)) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        $imageList = $this->getServiceLocator()->get('ProductAttachmentTable')->findAttachByProductIdForUploader($product->id);
        $form = new ProductActionForm();
        $form->setAttribute('action', $this->url()->fromRoute('shopping-cart', array('action' => 'add-shopping-cart', 'slug' => $product->slug)));
        $form->get('id')->setValue($product->id);
        $form->get('submit')->setAttribute('class', 'btn btn-primary');
        return array(
            'imageList' => $imageList,
            "product" => $product,
            'form' => $form
        );
    }
}
