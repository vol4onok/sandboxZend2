<?php

namespace Application\Model;
use Application\Model\AppTable;
use Zend\Db\Sql\Select;

class ProductAttachmentTable extends AppTable {

   
    protected $goodFields = array(
        'product_id',
        'attachment_id',
    );

    public function __construct() {
        parent::__construct('product_attachment');
    }

    public function findAttachByProductIdForUploader($productId) {
        $result = $this->select(function (Select $select) use ($productId) {
            $select->join('attachment', $this->getTable()  .'.attachment_id = attachment.id', array('id', 'resource'));
            $select->where(array('product_attachment.product_id' => $productId));
            $select->columns(array());
        });
        if ($result->count() > 0) {
            $data = array();
            foreach ($result as $value) {
                $data[$value['id']] = $value['resource'];
            }
            return $data;
        }
        return array();
    }
    
    /**
     * 
     * @param type $productId
     * @param type $imageIds
     */
    public function updateProductImageList($productId, $imageIds)
    {
        $this->delete(array('attachment_id' => $imageIds));
        foreach ($imageIds as $imageId) {
            $productAttachData = array('product_id' => $productId, 'attachment_id' => $imageId);
            $this->insert($productAttachData);
        }
    }
}
