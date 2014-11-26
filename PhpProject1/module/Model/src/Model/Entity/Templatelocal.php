<?php

namespace Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Templatelocal
 *
 * @ORM\Table(name="templatelocal", uniqueConstraints={@ORM\UniqueConstraint(name="id_2", columns={"id", "lang"})}, indexes={@ORM\Index(name="lang", columns={"lang"}), @ORM\Index(name="id", columns={"id"})})
 * @ORM\Entity(repositoryClass="Model\Repository\TemplatelocalRepository")
 */
class Templatelocal extends Raw\Templatelocal
{
    
}
