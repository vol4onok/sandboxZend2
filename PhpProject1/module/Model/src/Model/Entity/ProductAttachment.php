<?php

namespace Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProductAttachment
 *
 * @ORM\Table(name="product_attachment", indexes={@ORM\Index(name="product_id", columns={"product_id", "attachment_id"}), @ORM\Index(name="attachment_id", columns={"attachment_id"}), @ORM\Index(name="IDX_EA3886904584665A", columns={"product_id"})})
 * @ORM\Entity(repositoryClass="Model\Repository\ProductAttachmentRepository")
 */
class ProductAttachment extends Raw\ProductAttachment
{
   
}
