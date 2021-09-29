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
     * @ORM\Column(name="author", type="text", length=65535, nullable=false)
     */
    private string $author;

    /**
     * @var string
     *
     * @ORM\Column(name="doi", type="text", length=65535, nullable=false)
     */
    private string $doi;

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
    public function __construct(string $name, string $author, string $doi)
    {
        $this->name = $name;
        $this->author = $author;
        $this->doi = $doi;
        $this->concept = new ArrayCollection();
    }

    /**
     * @param Concept[] concepts
     */
    public function addConcepts(array $concepts)
    {
        foreach ($concepts as $concept) {
            if ($this->concept->indexOf($concept) > -1) {
                continue;
            }

            $this->concept->add($concept);
        }
    }
}
