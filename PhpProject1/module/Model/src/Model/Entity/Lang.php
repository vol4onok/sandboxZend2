<?php

namespace Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lang
 *
 * @ORM\Table(name="lang")
 * @ORM\Entity(repositoryClass="Model\Repository\LangRepository")
 */
class Lang extends Raw\Lang
{
   
}
