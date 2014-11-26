<?php

namespace Model\Entity\Raw;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
class Product
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=50, nullable=false)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", precision=10, scale=0, nullable=false)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="currency", type="string", nullable=false)
     */
    private $currency;

    /**
     * @var \Model\Entity\ProductType
     *
     * @ORM\ManyToOne(targetEntity="Model\Entity\ProductType", inversedBy="products")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     * })
     */
    private $type;



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
     * Set slug.

     *
     * @param string $slug
     *
     * @return Product
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug.

     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set title.

     *
     * @param string $title
     *
     * @return Product
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.

     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description.

     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.

     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set price.

     *
     * @param float $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price.

     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set currency.

     *
     * @param string $currency
     *
     * @return Product
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency.

     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set type.

     *
     * @param \Model\Entity\ProductType $type
     *
     * @return Product
     */
    public function setType(\Model\Entity\Raw\ProductType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.

     *
     * @return \Model\Entity\ProductType
     */
    public function getType()
    {
        return $this->type;
    }
}
