<?php

namespace Model\Entity\Raw;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
class Lang
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
     * @ORM\Column(name="code", type="string", length=3, nullable=false)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="main", type="boolean", nullable=false)
     */
    private $main = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=5, nullable=false)
     */
    private $locale;



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
     * Set code.

     *
     * @param string $code
     *
     * @return Lang
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code.

     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set name.

     *
     * @param string $name
     *
     * @return Lang
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.

     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set main.

     *
     * @param boolean $main
     *
     * @return Lang
     */
    public function setMain($main)
    {
        $this->main = $main;

        return $this;
    }

    /**
     * Get main.

     *
     * @return boolean
     */
    public function getMain()
    {
        return $this->main;
    }

    /**
     * Set locale.

     *
     * @param string $locale
     *
     * @return Lang
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale.

     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }
}
