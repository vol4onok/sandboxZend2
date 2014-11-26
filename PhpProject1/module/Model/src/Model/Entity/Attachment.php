<?php

namespace Model\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Attachment
 *
 * @ORM\Table(name="attachment")
 * @ORM\Entity(repositoryClass="Model\Repository\AttachmentRepository")
 */
class Attachment extends Raw\Attachment
{
   
    
}
