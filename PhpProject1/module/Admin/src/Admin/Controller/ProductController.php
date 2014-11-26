<?php
namespace Admin\Controller;
use Grid\ScaffoldAbstractController;

class ProductController extends ScaffoldAbstractController {

    protected $productTable;
	public function ready() {
		parent::ready();
		$this->_entityName = 'Model\Entity\Product';
        $this->setForm($this->getServiceLocator()->get('Admin\Form\ProductForm'));
        $this->productTable = $this->getServiceLocator()->get('ProductTable');
	}
	public function editAction() {
        
        $form = $this->getForm();
        $form->getHydrator()->addStrategy('attachments', new \Model\StrategyJson());
        $id = (int) $this->params('id',0);
		if ($id > 0 && $product = $this->getEntityManager()->getRepository('Model\Entity\Product')->getProductById($id)) {
            $form->bind($product); 
        } else {
            $form->bind(new \Model\Entity\Product());
        }
		$wasAdded = false;
		if ($this->request->isPost()) {
            $data = $this->request->getPost()->toArray();
            $attachments = json_decode($data['attachments'], JSON_FORCE_OBJECT);
            $data['attachments'] = array_flip($attachments);
            $form->bindValues($data);
            if(isset($data['submit'])) {
                $form->setInputFilter($form->getInpFilter());
                if ($form->isValid()) {
                    $data = $form->getData();
                    $this->getEntityManager()->persist($data);
                    $this->getEntityManager()->flush();
                }
			}
		}

		$canClosePage = !count($form->getMessages());
		
		$this->layout('layout/iframe');
		return array(
			'form' => $form,
			'canClosePage' => $canClosePage,
			'error' => $this->error,
			'title' => _('Product'),
			'activeLang' =>\Zend\Registry::get('lang'),
			'wasAdded' => $wasAdded,
		);
	}
}