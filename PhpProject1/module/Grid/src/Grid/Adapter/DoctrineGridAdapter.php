<?php

namespace Grid\Adapter;
use Grid\Adapter\GridAdapterInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\Config\Config;

/**
 * Description of DoctrineGridAdapter
 *
 * @author alexander
 */
class DoctrineGridAdapter implements GridAdapterInterface {
    
    
    const LIMIT = 20;
    const OFFSET = 0;
    const ORDER_BY = 1;
    const ORDER = "DESC";
    /**
     *
     * @var \Zend\ServiceManager\ServiceManager
     */
    protected $_sm;
    
    protected $_entity;
    
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;


    public function __construct(ServiceManager $sm) {
        $this->_sm = $sm;
        $this->entityManager = $this->_sm->get('doctrine.entitymanager.orm_default');
    }
    
    /**
     * 
     * @param \Zend\Config\Config $setting
     * @return type
     */
    public function getDataListForSetting(Config $setting)
    {
        /**
         * set default query params
         */
        if (!isset($setting->default)) {
            throw new Exception('Set to grid entity default settings');
        }
        $limitDefault   = $setting->get('default')->get('count', self::LIMIT);
        $offsetDefault  = $setting->get('default')->get('posStart',self::OFFSET);
        $orderByDefault = ($setting->get('default')->get('orderBy',self::ORDER_BY) - 1);
        $orderDefault   = $setting->get('default')->get('order', self::ORDER);
        
        /**
         * array field => column
         */
        $fieldNames = $this->getEntityManager()->getClassMetadata($this->_entity)->fieldNames;

        /**
         * get query params
         */
        $limit = $this->getRequest()->getQuery('count', $limitDefault);
        $offset = $this->getRequest()->getQuery('posStart', $offsetDefault);
        $orderBy = $this->getRequest()->getQuery('orderby', $orderByDefault);
        $order = null;
        if (
                isset($setting->get('scheme')->$orderBy) && 
                $setting->get('scheme')->get($orderBy)->get('entity') == 'field' && 
                isset($setting->get('scheme')->get($orderBy)->name)
            ) {
            $order = $this->getRequest()->getQuery('order', $orderDefault);
            $orderBy = $setting->get('scheme')->get($orderBy)->name;
        } else {
            $orderBy = null;
        }
        $queryParamList = $this->getQueryFilterParam($setting->get('scheme'));
        $result = $this->getEntityList($queryParamList, $orderBy, $order, $limit, $offset);
        $total  = $this->getCountEntity($queryParamList);
        return array(
            'list'  => $result,
            'total' => $total,
            'pos'  => $offset,
            'fieldListGet' => $fieldNames,
        );
    }
    
    
    /**
     * 
     * @return type
     */
    public function getTableName()
    {
        return strtolower($this->getEntityManager()->getClassMetadata($this->_entity)->getTableName());
    }
    
    /**
     * 
     * @param type $entity
     */
    public function setTableName($entity)
    {
        $this->_entity = $entity;
    }
    
    /**
     * 
     * @param \Zend\Config\Config $fieldList
     * @return array
     */
    protected function getQueryFilterParam(Config $fieldList)
    {
        $param = array('where' => array(), 'like' => array());
        foreach ($fieldList as $entity) {
            if ($entity->get('entity') == 'field' && isset($entity->filter) && $this->getRequest()->getQuery('fl' . $entity->get('name'), null) != null && $this->getRequest()->getQuery('fl' . $entity->get('name'), null) != 'all') {
                $type = ($entity->get('filter')->get('name') == 'input') ? 'like' : 'where';
                $param[$type][$entity->get('name')] = $this->getRequest()->getQuery('fl' . $entity->get('name'));
            } elseif ($this->getRequest()->getQuery('fl' . $entity->get('name'), null) != null && $this->getRequest()->getQuery('fl' . $entity->get('name')) != 'all') {
                $param['where'][$entity->get('name')] = $this->getRequest()->getQuery('fl' . $entity->get('name'));
            }
        }
        return $param;
    }

        /**
     * 
     * @return type
     */
    protected function getEntityManager()
    {
        return $this->entityManager;
    }
    
    /**
     * 
     * @return \Zend\Http\PhpEnvironment\Request
     */
    protected function getRequest()
    {
        return $this->_sm->get('Request');
    }
    
    protected function getCountEntity($queryParamList = array()) {
        
        $query = $this->getEntityManager()->
                getRepository($this->_entity)->
                createQueryBuilder('r')
                ->select('COUNT(r)');
        foreach ($queryParamList['where'] as $key => $value) {
            $query->andWhere("r.{$key}=:$key");
            $query->setParameter($key, $value);
        }
        foreach ($queryParamList['like'] as $key => $value) {
            $query->andWhere("r.{$key} LIKE :$key");
            $query->setParameter($key, "%{$value}%");
        }
        return $query->getQuery()
                ->getSingleScalarResult();
        
    }
    
    protected function getEntityList($queryParamList = array(), $orderBy = null, $order = "desc", $limit = null, $offset = null) {
        
        $query = $this->getEntityManager()->getRepository($this->_entity)->
                createQueryBuilder('r');
        if ($orderBy) {
            $query->orderBy('r.' .$orderBy, $order);
        }
        if ($limit) {
            $query->setMaxResults($limit);
        }
        if ($offset) {
            $query->setFirstResult($offset);
        }
        foreach ($queryParamList['where'] as $key => $value) {
            $query->where("r.{$key}=:$key");
            $query->setParameter($key, $value);
        }
        foreach ($queryParamList['like'] as $key => $value) {
            $query->andWhere("r.{$key} LIKE :$key");
            $query->setParameter($key, "%{$value}%");
        }
        return $query->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
    }
}
