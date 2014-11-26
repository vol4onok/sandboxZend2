<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Grid
 *
 * @author alexander
 */
namespace Grid;
use Grid\Adapter\GridAdapterInterface;
use Zend\Config\Config;
use Zend\ServiceManager\ServiceManager;

class Grid {
    
    protected $_entity;
    
    /**
     *
     * @var \Zend\ServiceManager\ServiceManager
     */
    protected $_sm;
    
    /**
     *
     * @var \Zend\Config\Config 
     */
    protected $_confGlobal;
    /**
     *
     * @var \Zend\Config\Config 
     */
    protected $_confEntity;
    /**
     *
     * @var \Grid\Adapter\GridAdapterInterface
     */
    protected $_adapter;
    
    public function __construct(ServiceManager $sm) 
    {
        $this->_sm = $sm;
        /* Set global conf */
        $conf = $this->getServiceManager()->get('Config');
        $this->_confGlobal = new Config($conf['mygrid']);
    }
    
    public function setAdapter(GridAdapterInterface $adapter)
    {
        $this->_adapter = $adapter;
    }

    public function setEntity($entity)
    {
        $this->_adapter->setTableName($entity);
        $this->_entity = $entity;
        /* Set Entity setting */
        if (!file_exists($this->getPathEntitySetting())) {
            throw new \Exception('No found grid setting ' . $this->getPathEntitySetting());
        }
        $grid = new Config(require_once $this->getPathEntitySetting(), true);
        if (!isset($grid->grid)) {
            throw new \Exception('Not found grid param in ' . $this->getPathEntitySetting());
        }
        $this->_confEntity = $this->checkPrivacy($grid->get('grid'));
        return $this;
    }
    
    public function getGridSettings()
    {
        $grid   = array();
        if (isset($this->_confGlobal->setting)) {
            $grid['setting']   = $this->_confGlobal->get('setting');
        }
        $grid['grid']   = $this->_confEntity;
        return $grid;
    }
    
    public function getGridEntityList()
    {
        $setting = $this->_confEntity;
        $result = $this->_adapter->getDataListForSetting($setting);
        $result['scheme'] = $this->_confEntity->get("scheme");
        return $result;
    }

    protected function getServiceManager()
    {
        return $this->_sm;
    }
    
    protected function getPathEntitySetting()
    {
        return $this->_confGlobal['sourcePath'] . $this->_adapter->getTableName() . '.php';
    }
    
    protected function checkPrivacy (Config $grid)
    {
        $scheme = $grid->get('scheme');
        $removeFieldIdList = array();
        foreach ($scheme as $key => $entity) {
            if(isset($entity->isAllowed) && !$this->getServiceManager()->get('User')->isAllowed($entity->get('isAllowed')->get('controller'), $entity->get('isAllowed')->get('action'))) {
                $removeFieldIdList[] = $key;
            }
        }
        if ($removeFieldIdList && is_array($removeFieldIdList)) {
            foreach ($grid as $type) {
                foreach ($removeFieldIdList as $removeFieldId) {
                    unset($type->$removeFieldId);
                }
            }
        }
        return $grid;
    }
}