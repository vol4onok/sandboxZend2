<?php

namespace Model\Entity\Raw;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
class Attachment
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
     * @ORM\Column(name="resource", type="string", length=255, nullable=false)
     */
    private $resource;



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
     * Set resource.

     *
     * @param string $resource
     *
     * @return Attachment
     */
    public function setResource($resource)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * Get resource.

     *
     * @return string
     */
    public function getResource()
    {
        return $this->resource;
    }
}
