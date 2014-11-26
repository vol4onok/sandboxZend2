<?php

namespace Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Template
 *
 * @ORM\Table(name="template", uniqueConstraints={@ORM\UniqueConstraint(name="name", columns={"name"})})
 * @ORM\Entity(repositoryClass="Model\Repository\TemplateRepository")
 */
class Template extends Raw\Template
{
  
}
