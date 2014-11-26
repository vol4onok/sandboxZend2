<?php

namespace Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductType
 *
 * @ORM\Table(name="product_type")
 * @ORM\Entity(repositoryClass="Model\Repository\ProductTypeRepository")
 */
class ProductType extends Raw\ProductType
{
    /**
     * @ORM\OneToMany(targetEntity="Model\Entity\Product", mappedBy="type")
     */
    private $products;
    
    public function __construct() {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getProducts()
    {
        return $this->products;
    }
    
}
