<?php
namespace Admin\Controller;
use Application\Lib\AppController;
use Application\Model\ProductTable;
use Application\Lib\Tools\Image\SimpleImage;
class ProductController extends AppController {
	/**
	* @var \Zend\Form\Form
	*/
	var $form;
	
	/**
	 * @var Application\Model\TemplateTable
	 */
	var $productTable;
	
	var $url = 'product';

	public function ready() {
		parent::ready();

		$this->productTable = new ProductTable();
	}
	
	/**
	 * return form
	 * 
	 * @return \Zend\Form\Form
	 */
	public function getForm() {
		if(is_null($this->form)) {
			$this->form = $this->getServiceLocator()->get('Admin\Form\ProductForm');
		}
		return $this->form;
	}

	public function indexAction() {
		$this->layout()->bodyClass = 'products';
		$this->renderHtmlIntoLayout('submenu', 'admin/product/submenu.phtml');
	}

	public function editAction() {
        
        $form = $this->getForm();

		$id = (int) $this->params('id',0);
		if ($id > 0) {
            $product = $this->productTable->getProductById($id);
            if ($product->count()) {
                $form->setData($product->current());
                //json image {id: resourse}
                $jsonImage = $this->getServiceLocator()->get("ProductAttachmentTable")->findAttachByProductIdForUploader($product->current()->id);
                $jsonImage = json_encode($jsonImage, JSON_FORCE_OBJECT);
                $form->get('attachment')->setValue($jsonImage);
            } else {
                $this->error = _('Product not found1');
            }
        }
		$wasAdded = false;
		if ($this->request->isPost()) {
            $data = array_merge_recursive(
                $this->request->getPost()->toArray(),
                $this->request->getFiles()->toArray()
            );
            $form->setData($data);
            if(isset($data['submit'])) {
                $form->setInputFilter($form->getInpFilter());
				if ($form->isValid()) {
                    $data = $form->getData();
                    $data['type_id'] = (int) $data['type_id'];
					if ($id > 0){
                        $this->productTable->setId($id);
						$this->productTable->set($data);
					}
					else {
						$id = $this->productTable->insert($data);
						$form->setAttribute('action', $this->url()->fromRoute('admin', array('controller' => 'product', 'action' => 'edit', 'id' => $id)));
						$form->get('id')->setValue($id);
						$wasAdded = true;
					}
                    //delete cache
                    $slug = $data['slug'];
                    $this->productTable->cacheDelete($this->productTable->getTable() . '.slug.' . $slug);
                    $imageArray = json_decode($form->get('attachment')->getValue(), true);
                    $imageIds = array_keys($imageArray);
                    if ($imageIds) {
                        $this->getServiceLocator()->get('ProductAttachmentTable')->updateProductImageList($id, $imageIds);
                    }
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

	public function deleteAction() {
		$id = (int)$this->request->getPost('id');

        $product = $this->productTable->getProductById($id);
        //delete cache
        if ($product->count()) {
            $slug = $product->current()->slug;
            $this->productTable->cacheDelete($this->productTable->getTable() . '.slug.' . $slug);
            $this->productTable->delete(array(
                'id' => $id,
            ));
        }
        return $this->getResponse()->setContent('OK');
	}

	public function listAction() {
		header("Content-Type: text/xml");
		$count=50;
		if (isset($_GET['count']))
			$count=(int)$_GET['count'];
		$pos = 0;
		if (isset($_GET['posStart']))
			$pos=(int)$_GET['posStart'];
		$orderby=$this->productTable->table.".id";

		$params = array();

		if(isset($_GET['orderby'])) {
			switch($_GET['order']) {
				case 'asc': $orderdir='asc'; break;
				default: $orderdir='desc';
			}
			switch($_GET['orderby']) {
				case 1: $orderby=$this->productTable->table.".id"; break;
				default: $orderby=$this->productTable->table.".id"; break;
			}
			$orderby.=' '.$orderdir;
		}
		$total = 0;
		$list = $this->productTable->getProductList($params, $count, $pos, $orderby);

        echo '<?xml version="1.0" encoding="UTF-8"?>';
		echo  $this->renderView('admin/product/list.phtml', array(
            'list'  => $list,
            'total' => $total,
            'pos'  => $pos,
        ));
        exit();
	}
}
