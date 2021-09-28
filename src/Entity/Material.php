<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Material
 *
 * @ORM\Table(name="material")
 * @ORM\Entity
 */
class Material
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
     * @var string
     *
     * @ORM\Column(name="link", type="text", length=65535, nullable=false)
     */
    private string $link;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Concept", inversedBy="material")
     * @ORM\JoinTable(name="annotation",
     *   joinColumns={
     *     @ORM\JoinColumn(name="material_fk", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="concept_fk", referencedColumnName="id")
     *   }
     * )
     */
    private Collection $concept;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->concept = new ArrayCollection();
    }

}
