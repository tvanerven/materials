<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use OpenApi\Annotations as OA;

/**
 * Material
 *
 * @ORM\Table(name="material")
 * @ORM\Entity(repositoryClass="App\Repository\MaterialRepository")
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
     * @ORM\Column(name="date", type="date", nullable=false)
     * @JMS\Type("DateTime<'Y-m-d'>")
     * @OA\Property(example="2021-12-25")
     */
    private DateTime $date;

    /**
     * @var Concept[]
     */
    private array $concept = [];

    /**
     * Constructor
     */
    public function __construct(string $name, string $author, string $doi, DateTime $date)
    {
        $this->name = $name;
        $this->author = $author;
        $this->doi = $doi;
        $this->date = $date;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getDoi(): string
    {
        return $this->doi;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function addConcept(Concept $concept): void
    {
        array_push($this->concept, $concept);
    }
}
