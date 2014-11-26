<?php

namespace Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ShoppingCart
 *
 * @ORM\Table(name="shopping_cart", uniqueConstraints={@ORM\UniqueConstraint(name="id_product", columns={"id_product", "id_user"})}, indexes={@ORM\Index(name="id_user", columns={"id_user"}), @ORM\Index(name="IDX_72AAD4F6DD7ADDD", columns={"id_product"})})
 * @ORM\Entity(repositoryClass="Model\Repository\ShoppingCartRepository")
 */
class ShoppingCart extends Raw\ShoppingCart
{
    
}
