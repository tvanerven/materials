<?php

namespace App\Repository;

use App\Entity\Annotation;
use App\Entity\Concept;
use App\Entity\Material;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Material|null find($id, $lockMode = null, $lockVersion = null)
 * @method Material|null findOneBy(array $criteria, array $orderBy = null)
 * @method Material[]    findAll()
 * @method Material[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Material::class);
    }

    /**
     * @param string[] $rdfAboutConceptFilter
     * @return Material[]
     */
    public function findAllMaterial(array $rdfAboutConceptFilter): array
    {


        $annotations = $this->getAnnotations($rdfAboutConceptFilter);
        $materials = [];

        foreach ($annotations as $annotation) {
            $material = $annotation->getMaterial();
            $concept = $annotation->getConcept();

            $alreadyInsertedMaterial = $this->getMaterialByIdInArray($material->getId(), $materials);

            if ($alreadyInsertedMaterial === null) {
                $material->addConcept($concept);
                array_push($materials, $material);
            } else {
                $alreadyInsertedMaterial->addConcept($concept);
            }
        }

        return $materials;
    }

    /**
     * @param string[] $rdfAboutConceptFilter
     * @return Annotation[]
     */
    private function getAnnotations(array $rdfAboutConceptFilter): array
    {
        $em = $this->getEntityManager();
        $query = 'SELECT a FROM App\Entity\Annotation a';

        if (count($rdfAboutConceptFilter) === 0) {
            return $em->createQuery($query)->getResult();
        }

        $concepts = $em->getRepository(Concept::class)->getConceptsByLabel($rdfAboutConceptFilter);
        if (count($concepts) === 0) {
            return [];
        }
        
        $query .= ' WHERE';

        for ($i = 0; $i < count($concepts); $i++) {
            if ($i !== 0) {
                $query .= ' AND';
            }

            $query .= " IDENTITY(a.material) IN (SELECT IDENTITY(a$i.material) FROM App\Entity\Annotation a$i WHERE a$i.concept = :concept$i)";
        }

        $qb = $em->createQuery($query);

        for ($i = 0; $i < count($concepts); $i++) {
            $qb->setParameter("concept$i", $concepts[$i]);
        }

        return $qb->getResult();
    }

    /**
     * @param int $materialId
     * @param Material[] $materials
     */
    private function &getMaterialByIdInArray(int $materialId, array &$materials): ?Material
    {
        $alreadyInsertedMaterial = null;
        foreach ($materials as $material) {
            if ($material->getId() === $materialId) {
                $alreadyInsertedMaterial = $material;
            }
        }
        return $alreadyInsertedMaterial;
    }
}
