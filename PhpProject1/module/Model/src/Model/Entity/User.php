<?php

namespace Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Model\Repository\UserRepository")
 */
class User extends Raw\User
{
}
