<?php

namespace Model\Entity\Raw;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
class ShoppingCart
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
     * @var integer
     *
     * @ORM\Column(name="count", type="integer", nullable=false)
     */
    private $count = '1';

    /**
     * @var \Model\Entity\Raw\User
     *
     * @ORM\ManyToOne(targetEntity="Model\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     * })
     */
    private $idUser;

    /**
     * @var \Model\Entity\Raw\Product
     *
     * @ORM\ManyToOne(targetEntity="Model\Entity\Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_product", referencedColumnName="id")
     * })
     */
    private $idProduct;



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
     * Set count.

     *
     * @param integer $count
     *
     * @return ShoppingCart
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count.

     *
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set idUser.

     *
     * @param \Model\Entity\Raw\User $idUser
     *
     * @return ShoppingCart
     */
    public function setIdUser(\Model\Entity\Raw\User $idUser = null)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get idUser.

     *
     * @return \Model\Entity\Raw\User
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set idProduct.

     *
     * @param \Model\Entity\Raw\Product $idProduct
     *
     * @return ShoppingCart
     */
    public function setIdProduct(\Model\Entity\Raw\Product $idProduct = null)
    {
        $this->idProduct = $idProduct;

        return $this;
    }

    /**
     * Get idProduct.

     *
     * @return \Model\Entity\Raw\Product
     */
    public function getIdProduct()
    {
        return $this->idProduct;
    }
}
