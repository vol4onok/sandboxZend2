<?php

namespace Application\Model;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\DbTableGateway;
use Zend\Db\Sql\Select;


class ProductTable extends AppTable{

    
    protected $goodFields = array(
        'id',
        'slug',
        'title',
        'type_id',
        'description',
        'price',
        'currency',
    );

    /**
     * @param int $id
     */
    public function __construct($id = null) {
        parent::__construct('product', $id);
    }

    /**
     * 
     * @param int $pageRange
     * @param int $pageNumber
     * @return \Zend\Paginator\Paginator
     */
    public function getPaginatorRows($pageRange = 10, $pageNumber = 1) {
        $dbTableGatewayAdapter = new DbTableGateway($this);
        $paginator = new Paginator($dbTableGatewayAdapter);
        $paginator->setCurrentPageNumber($pageNumber);
        $paginator->setItemCountPerPage($pageRange);
        return $paginator;
    }
    
    public function getPaginator($params, $pageRange = 10, $pageNumber = 1) {
        $select = new Select($this->getTable());
        $select->join('product_type', $this->getTable() . '.type_id = product_type.id', array('categoryName' => 'title', 'categoryId' => 'id'));
        $select->join('product_attachment', $this->getTable() . '.id = product_attachment.product_id', array(), $select::JOIN_LEFT);
        $select->join('attachment', 'product_attachment.attachment_id = attachment.id', array('resource'), $select::JOIN_LEFT);
        $select->where('product_type.status = 1');
        $select->where($params);
        $select->group($this->getTable() . '.id');
        $paginator = new \Zend\Paginator\Paginator(
            new \Zend\Paginator\Adapter\DbSelect(
                $select,
                $this->getAdapter()
            )
        );
        $paginator->setCurrentPageNumber($pageNumber);
        $paginator->setItemCountPerPage($pageRange);
        return $paginator;
    }

    public function getProductById($id, $columns=array('*'))
    {
        $result = $this->select(function (Select $select) use ($id, $columns) {

            $select->join('product_type', $this->getTable() . '.type_id = product_type.id', array('type' => 'title'));
            $select->where(array($this->getTable() . '.id' => $id));
            $select->columns($columns);
        });
        return $result;
    }

    public function getProductList($params, $limit=null, $offset=null, $orderBy=false, $columns=array('*'))
    {
        $result = $this->select(function (Select $select) use ($params, $limit, $offset, $orderBy, $columns) {
            $select->join('shopping_cart', $this->getTable() . '.id = shopping_cart.id_product', array('count'), $select::JOIN_LEFT);
            $select->join('product_attachment', $this->getTable() . '.id = product_attachment.product_id', array(), $select::JOIN_LEFT);
            $select->join('attachment', 'product_attachment.attachment_id = attachment.id', array('resource'), $select::JOIN_LEFT);
            $select->join('product_type', $this->getTable() . '.type_id = product_type.id', array('type' => 'title'));
            $select->group($this->getTable() . '.id');
            $select->where($params);
            if ($limit) {
                $select->limit($limit);
            }
            if ($offset) {
                $select->offset($offset);
            }
            if ($orderBy) {
                $select->order($orderBy);
            }
            $select->columns($columns);
        });
        return $result;
    }
    
    public function getProductBySlug($slug, $columns=array('*'))
    {
        $result = $this->cacheGet($this->getTable() . '.slug.' . $slug);
        if (!$result) {
            $result = $this->select(function (Select $select) use ($slug, $columns) {
                $select->join('shopping_cart', $this->getTable() . '.id = shopping_cart.id_product', array('count'), $select::JOIN_LEFT);
                $select->join('product_attachment', $this->getTable() . '.id = product_attachment.product_id', array(), $select::JOIN_LEFT);
                $select->join('attachment', 'product_attachment.attachment_id = attachment.id', array('resource'), $select::JOIN_LEFT);
                $select->join('product_type', $this->getTable() . '.type_id = product_type.id', array('type' => 'title'));
                $select->where(array('product_type.status' => 1));
                $select->group($this->getTable() . '.id');
                $select->where(array('slug' => $slug));
                $select->columns($columns);
            });
            if ($result->count()) {
                $result = $result->current();
                $this->cacheSet($this->getTable() . '.slug.' . $slug, $result);
            } else {
                return false;
            }
        }
        
        return $result;
    }
}
