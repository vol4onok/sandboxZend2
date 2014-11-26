<?php
namespace Grid;
use Application\Lib\AppController;
/**
 * Description of ScaffoldAbstractController
 *
 * @author alexander
 */

abstract class ScaffoldAbstractController extends AppController
{
    protected $_entityName;
    protected $_form;
    
    public function indexAction() {
        $data = $this->getServiceLocator()->get('MyGrid')->setEntity($this->_entityName)->getGridSettings();
        $this->renderHtmlIntoLayout('submenu', 'grid/entity/submenu.phtml');
        return $this->render('grid/entity/index.phtml', $data);
    }
    
    public function listAction() {
		header("Content-Type: text/xml");
        $result = $this->getServiceLocator()->get('MyGrid')->setEntity($this->_entityName)->getGridEntityList();
		 echo '<?xml version="1.0" encoding="UTF-8"?>';
		echo  $this->renderView('grid/entity/list.phtml', $result);
        exit();
	}
    
    public function editAction()
    {
        $id = (int) $this->params('id',0);
        $form = $this->getForm();
        if ($id > 0) {
            $entity = $this->getEntityManager()->find($this->_entityName, $id);
            if ($entity) {
                $form->bind($entity);
            } else {
                $this->error = _('Product not found');
            }
            
        } else {
            $form->setObject(new $this->_entityName());
        }
        if ($this->request->isPost()) {
            $data = $this->request->getPost()->toArray();
            $form->bindValues($data);
            if ($form->isValid()) {
                $entity = $form->getData();
                $this->getEntityManager()->flush($entity);
            }
        }
        $canClosePage = !count($form->getMessages());
        return $this->renderLayout('layout/iframe.phtml', 'grid/entity/edit.phtml', array(
			'form' => $form,
			'canClosePage' => $canClosePage,
			'error' => $this->error,
			'title' => _('Product'),
		));
    }
    
    public function deleteAction()
    {
        $id = (int) $this->getRequest()->getPost('id');
        $entity = $this->getEntityManager()->getRepository($this->_entityName)->find($id);
        if ($entity) {
            $this->getEntityManager()->remove($entity);
            $this->getEntityManager()->flush();
        }
        return $this->getResponse()->setContent('OK');
    }
    
    /**
     * 
     * @return \Zend\Form\Form
     */
    protected function getForm()
    {
        return $this->_form;
    }
    
    /**
     * 
     * @param \Zend\Form\Form $form
     */
    protected function setForm(\Zend\Form\Form $form)
    {
        $form->setHydrator(new \DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity($this->getEntityManager()));
        $this->_form = $form;
    }
}
