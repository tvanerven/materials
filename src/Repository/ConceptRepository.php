<?php

namespace App\Repository;

use App\Entity\Concept;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Concept|null find($id, $lockMode = null, $lockVersion = null)
 * @method Concept|null findOneBy(array $criteria, array $orderBy = null)
 * @method Concept[]    findAll()
 * @method Concept[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConceptRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Concept::class);
    }

    /**
     * @param string[] $rdfAboutList
     * @return Concept[]
     */
    public function getConceptsByLabel(array $rdfAboutList): array
    {
        if (count($rdfAboutList) === 0) {
            return [];
        }

        $qb = $this->getEntityManager()
            ->createQuery('SELECT c FROM App\Entity\Concept c WHERE c.rdfAbout IN (:rdfAboutList)')
            ->setParameter('rdfAboutList', $rdfAboutList);

        return $qb->getResult();
    }
}