<?php

namespace Model\Entity\Raw;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
class ProductAttachment
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Model\Entity\Raw\Product
     *
     * @ORM\ManyToOne(targetEntity="Model\Entity\Raw\Product", inversedBy="attachments")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * })
     */
    private $product;

    /**
     * @var \Model\Entity\Raw\Attachment
     *
     * @ORM\ManyToOne(targetEntity="Model\Entity\Attachment")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="attachment_id", referencedColumnName="id")
     * })
     */
    private $attachment;



    /**
     * Get id.

     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set product.

     *
     * @param \Model\Entity\Raw\Product $product
     *
     * @return ProductAttachment
     */
    public function setProduct(\Model\Entity\Raw\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product.

     *
     * @return \Model\Entity\Raw\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set attachment.

     *
     * @param \Model\Entity\Raw\Attachment $attachment
     *
     * @return ProductAttachment
     */
    public function setAttachment(\Model\Entity\Raw\Attachment $attachment = null)
    {
        $this->attachment = $attachment;

        return $this;
    }

    /**
     * Get attachment.

     *
     * @return \Model\Entity\Raw\Attachment
     */
    public function getAttachment()
    {
        return $this->attachment;
    }
}
