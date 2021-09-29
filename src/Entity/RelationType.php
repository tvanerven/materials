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
     * @ORM\Column(name="label", type="text", length=65535, nullable=true)
     */
    private string $label;

    /**
     * @var string|null
     *
     * @ORM\Column(name="rdf_about", type="text", length=65535, nullable=false)
     */
    private ?string $rdfAbout;


    public function __construct(string $label, ?string $rdfAbout = null)
    {
        $this->label = $label;
        $this->rdfAbout = $rdfAbout;
    }


    public function getLabel(): string
    {
        return $this->label;
    }
}
