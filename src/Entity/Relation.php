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
     * @var \RelationType
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="RelationType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="relation_type_fk", referencedColumnName="id")
     * })
     */
    private $relationType;

    /**
     * @var \Concept
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Concept")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="source_fk", referencedColumnName="id")
     * })
     */
    private $source;

    /**
     * @var \Concept
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Concept")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="target_fk", referencedColumnName="id")
     * })
     */
    private $target;


}
