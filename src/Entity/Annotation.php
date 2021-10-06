<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Annotation
 *
 * @ORM\Table(name="annotation",
 * indexes={
 *  @ORM\Index(name="annotation_concept_fk", columns={"concept_fk"}),
 *  @ORM\Index(name="annotation_material_fk", columns={"material_fk"})})
 * @ORM\Entity
 */
class Annotation
{
    /**
     * @var Material
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Material")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="material_fk", referencedColumnName="id")
     * })
     */
    private Material $material;

    /**
     * @var Concept
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Concept")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="concept_fk", referencedColumnName="id")
     * })
     */
    private Concept $concept;

    public function __construct(Material $material, Concept $concept)
    {
        $this->material = $material;
        $this->concept = $concept;
    }

    /**
     * @return  Material
     */
    public function getMaterial(): Material
    {
        return $this->material;
    }

    /**
     * @return  Concept
     */
    public function getConcept(): Concept
    {
        return $this->concept;
    }
}
