<?php

namespace App\Repository;

use App\Entity\Annotation;
use App\Entity\Concept;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Annotation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annotation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annotation[]    findAll()
 * @method Annotation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnotationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annotation::class);
    }

    /**
     * @param string[] $rdfAboutConceptFilter
     * @return Annotation[]
     */
    public function getAnnotations(array $rdfAboutConceptFilter): array
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

            $query .= " IDENTITY(a.material) IN (SELECT IDENTITY(a$i.material) FROM App\Entity\Annotation a$i WHERE a$i.concept IN (:concept$i))";
        }

        $qb = $em->createQuery($query);

        for ($i = 0; $i < count($concepts); $i++) {
            $conceptWithSubConcepts = $em->getRepository(Concept::class)->getConceptWithSubConcepts($concepts[$i]);
            $qb->setParameter("concept$i", $conceptWithSubConcepts);
        }

        return $qb->getResult();
    }
}
