<?php

namespace Application\Model;
use Application\Model\AppTable;
use Zend\Db\Sql\Select;

class AttachmentTable extends AppTable {

   
    protected $goodFields = array(
        'id',
        'resource',
    );

    public function __construct() {
        parent::__construct('attachment');
    }

    public function findOne($id) {
        $result = $this->select(function (Select $select) use ($id) {
            $select->where(array('id' => $id));
        });
        return $result;
    }
}
