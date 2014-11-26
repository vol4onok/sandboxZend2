<?php

namespace Model\Entity\Raw;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
class Templatelocal
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_i", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idI;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255, nullable=true)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", nullable=true)
     */
    private $text;

    /**
     * @var \Model\Entity\Raw\Lang
     *
     * @ORM\ManyToOne(targetEntity="Model\Entity\Raw\Lang")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="lang", referencedColumnName="id")
     * })
     */
    private $lang;

    /**
     * @var \Model\Entity\Raw\Template
     *
     * @ORM\ManyToOne(targetEntity="Model\Entity\Raw\Template")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id", referencedColumnName="id")
     * })
     */
    private $id;



    /**
     * Get idI.

     *
     * @return integer
     */
    public function getIdI()
    {
        return $this->idI;
    }

    /**
     * Set subject.

     *
     * @param string $subject
     *
     * @return Templatelocal
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject.

     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set text.

     *
     * @param string $text
     *
     * @return Templatelocal
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text.

     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set lang.

     *
     * @param \Model\Entity\Raw\Lang $lang
     *
     * @return Templatelocal
     */
    public function setLang(\Model\Entity\Raw\Lang $lang = null)
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * Get lang.

     *
     * @return \Model\Entity\Raw\Lang
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Set id.

     *
     * @param \Model\Entity\Raw\Template $id
     *
     * @return Templatelocal
     */
    public function setId(\Model\Entity\Raw\Template $id = null)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id.

     *
     * @return \Model\Entity\Raw\Template
     */
    public function getId()
    {
        return $this->id;
    }
}
