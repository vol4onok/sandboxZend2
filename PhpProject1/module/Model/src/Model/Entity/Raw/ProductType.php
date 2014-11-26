<?php

namespace Model\Entity\Raw;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
class ProductType
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=50, nullable=false)
     */
    private $title;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status = '0';



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
     * Set title.

     *
     * @param string $title
     *
     * @return ProductType
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
     * Set status.

     *
     * @param boolean $status
     *
     * @return ProductType
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.

     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }
}
