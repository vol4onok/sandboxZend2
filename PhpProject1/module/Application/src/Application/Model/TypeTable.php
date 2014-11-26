<?php

namespace Application\Model;
use Zend\Db\Sql\Select;

class TypeTable extends AppTable {
    
    protected $goodFields = array(
        'id',
        'title',
        'status',
    );

    public function __construct() {
        parent::__construct('product_type');
    }

    public function getCategoryById($id, $columns = array('*'))
    {
        $result = $this->select(function (Select $select) use ($id, $columns) {
            $select->where(array($this->getTable() . '.id' => $id));
            $select->columns($columns);
        });
        return $result;
    }
    
}
