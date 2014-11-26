<?php

namespace Model\Entity\Raw;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
class User
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var integer
     *
     * @ORM\Column(name="created", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $created;

    /**
     * @var string
     *
     * @ORM\Column(name="network", type="string", nullable=false)
     */
    private $network;

    /**
     * @var integer
     *
     * @ORM\Column(name="uidNetwork", type="bigint", nullable=false, options={"unsigned"=true})
     */
    private $uidnetwork;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255, nullable=false)
     */
    private $photo;

    /**
     * @var string
     *
     * @ORM\Column(name="level", type="string", nullable=false)
     */
    private $level = 'user';

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", nullable=false)
     */
    private $active = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="newpass", type="string", length=64, nullable=true)
     */
    private $newpass;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=64, nullable=false)
     */
    private $code;

    /**
     * @var integer
     *
     * @ORM\Column(name="country", type="integer", nullable=true, options={"unsigned"=true})
     */
    private $country = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=45, nullable=true)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=64, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=50, nullable=false)
     */
    private $phone;



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
     * Set name.

     *
     * @param string $name
     *
     * @return User
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
     * Set email.

     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.

     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set created.

     *
     * @param integer $created
     *
     * @return User
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created.

     *
     * @return integer
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set network.

     *
     * @param string $network
     *
     * @return User
     */
    public function setNetwork($network)
    {
        $this->network = $network;

        return $this;
    }

    /**
     * Get network.

     *
     * @return string
     */
    public function getNetwork()
    {
        return $this->network;
    }

    /**
     * Set uidnetwork.

     *
     * @param integer $uidnetwork
     *
     * @return User
     */
    public function setUidnetwork($uidnetwork)
    {
        $this->uidnetwork = $uidnetwork;

        return $this;
    }

    /**
     * Get uidnetwork.

     *
     * @return integer
     */
    public function getUidnetwork()
    {
        return $this->uidnetwork;
    }

    /**
     * Set photo.

     *
     * @param string $photo
     *
     * @return User
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo.

     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set level.

     *
     * @param string $level
     *
     * @return User
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level.

     *
     * @return string
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set active.

     *
     * @param boolean $active
     *
     * @return User
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active.

     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set newpass.

     *
     * @param string $newpass
     *
     * @return User
     */
    public function setNewpass($newpass)
    {
        $this->newpass = $newpass;

        return $this;
    }

    /**
     * Get newpass.

     *
     * @return string
     */
    public function getNewpass()
    {
        return $this->newpass;
    }

    /**
     * Set code.

     *
     * @param string $code
     *
     * @return User
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
     * Set country.

     *
     * @param integer $country
     *
     * @return User
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country.

     *
     * @return integer
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set login.

     *
     * @param string $login
     *
     * @return User
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login.

     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set password.

     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password.

     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set phone.

     *
     * @param string $phone
     *
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone.

     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }
}
