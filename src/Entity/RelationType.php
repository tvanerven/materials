<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RelationType
 *
 * @ORM\Table(name="relation_type")
 * @ORM\Entity
 */
class RelationType
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="text", length=65535, nullable=false)
     */
    private string $name;


}
