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
     * @ORM\Column(name="label", type="text", length=65535, nullable=false)
     */
    private string $label;

    /**
     * @var string
     *
     * @ORM\Column(name="rdf_about", type="text", length=65535, nullable=false)
     */
    private string $rdfAbout;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Material", mappedBy="concept")
     */
    private Collection $material;

    /**
     * Constructor
     */
    public function __construct(string $label, string $rdfAbout)
    {
        $this->label = $label;
        $this->rdfAbout = $rdfAbout;
        $this->material = new ArrayCollection();
    }

    public function getLabel(): string
    {
        return $this->label;
    }
}
