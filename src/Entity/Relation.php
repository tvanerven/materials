<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Relation
 *
 * @ORM\Table(name="relation", indexes={@ORM\Index(name="relation_target_fk", columns={"target_fk"}), @ORM\Index(name="relation_relation_type_fk", columns={"relation_type_fk"}), @ORM\Index(name="IDX_62894749821B1D3F", columns={"source_fk"})})
 * @ORM\Entity
 */
class Relation
{

    /**
     * @var Concept
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Concept")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="source_fk", referencedColumnName="id")
     * })
     */
    private Concept $source;

    /**
     * @var Concept
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Concept")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="target_fk", referencedColumnName="id")
     * })
     */
    private Concept $target;

    /**
     * @var RelationType
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="RelationType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="relation_type_fk", referencedColumnName="id")
     * })
     */
    private RelationType $relationType;

    public function __construct(Concept $source, Concept $target, RelationType $relationType) {
        $this->source = $source;
        $this->target = $target;
        $this->relationType = $relationType;
    }

    public function getSource(): Concept {
        return $this->source;
    }

    public function getTarget(): Concept
    {
        return $this->target;
    }

    public function getRelationType(): RelationType
    {
        return $this->relationType;
    }
}
