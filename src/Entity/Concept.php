<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Concept
 *
 * @ORM\Table(name="concept")
 * @ORM\Entity
 */
class Concept
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

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Material", mappedBy="concept")
     */
    private Collection $material;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->material = new ArrayCollection();
    }

}
