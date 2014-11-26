<?php

namespace Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Product
 *
 * @ORM\Table(name="product", uniqueConstraints={@ORM\UniqueConstraint(name="slug", columns={"slug"})}, indexes={@ORM\Index(name="type", columns={"type_id"})})
 * @ORM\Entity(repositoryClass="Model\Repository\ProductRepository")
 */
class Product extends Raw\Product
{
    
    /**
     * @ORM\ManyToMany(targetEntity="Model\Entity\Attachment", orphanRemoval=true)
     * @ORM\JoinTable(name="product_attachment",
     *  joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="attachment_id", referencedColumnName="id", unique=true)},
     * )
     */
    private $attachments;
    
    public function __construct() {
        $this->attachments = new ArrayCollection();
    }
    
    public function getAttachments()
    {
        return $this->attachments;
    }
    
    public function setAttachments($products)
    {
        return $this->attachments = $products;
    }
    
    public function setAttachment(\Model\Entity\Attachment $product)
    {
        return $this->attachments[] = $product;
    }
}
