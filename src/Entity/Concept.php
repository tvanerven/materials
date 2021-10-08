<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Concept
 *
 * @ORM\Table(name="concept")
 * @ORM\Entity(repositoryClass="App\Repository\ConceptRepository")
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
     * Constructor
     */
    public function __construct(string $label, string $rdfAbout)
    {
        $this->label = $label;
        $this->rdfAbout = $rdfAbout;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
