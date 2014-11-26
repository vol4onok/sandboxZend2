<?php

namespace Application\Model;


class ShoppingCartTable extends AppTable {

    const PRODUCT_SESSION_VAR = 'products';
    
    protected $goodFields = array(
        'id_product',
        'id_user',
        'count',
    );

    public function __construct() {
        parent::__construct('shopping_cart');
    }

    /**
     * 
     * @param type $userId
     * @param type $productId
     * @return type
     */
    public function addProduct($userId, $productId, $count = 1)
    {
        $productData = array('id_product' => $productId, 'id_user' => $userId, 'count' => $count);
        return $this->insertOrUpdate($productData, array('count' => $count), true);
    }
    
    /**
     * 
     * @param type $userId
     * @param type $productList
     */
    public function addProducts($userId, $productList)
    {
        foreach ($productList as $productId => $count) {
            $productData = array('id_product' => $productId, 'id_user' => $userId, 'count' => $count);
            $this->insertOrUpdate($productData, array('count' => $count), true);
        }
    }
    
    /**
     * 
     * @param type $userId
     * @param type $productId
     * @return type
     */
    public function deleteByProductId($userId, $productId)
    {
        $where = array(
            'id_user' => $userId,
            'id_product' => $productId,
        );
        return $this->delete($where);
    }
    
    /**
     * 
     * @param type $id
     */
    public function deleteSessionProductById($id)
    {
        unset($_SESSION[self::PRODUCT_SESSION_VAR][$id]);
    }
    
    /**
     * 
     * @param type $productId
     * @param type $count
     */
    public function addSessoinProduct($productId, $count = 1)
    {
        if (!isset($_SESSION[self::PRODUCT_SESSION_VAR][$productId])) {
                $_SESSION[self::PRODUCT_SESSION_VAR][$productId] = 0;
            }
            $_SESSION[self::PRODUCT_SESSION_VAR][$productId] += 1;
    }
    
    /**
     * 
     * @return type
     */
    public function getSessionProductList()
    {
        if (!empty($_SESSION[self::PRODUCT_SESSION_VAR])) {
            return $_SESSION[self::PRODUCT_SESSION_VAR];
        }
        return array();
    }
    
    public function unsetSessionProductList()
    {
        unset($_SESSION[self::PRODUCT_SESSION_VAR]);
    }
}
