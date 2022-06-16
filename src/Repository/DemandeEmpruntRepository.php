<?php

namespace App\Repository;

use App\Entity\DemandeEmprunt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DemandeEmprunt>
 *
 * @method DemandeEmprunt|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemandeEmprunt|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemandeEmprunt[]    findAll()
 * @method DemandeEmprunt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandeEmpruntRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemandeEmprunt::class);
    }

    public function add(DemandeEmprunt $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DemandeEmprunt $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return DemandeEmprunt[] Returns an array of DemandeEmprunt objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DemandeEmprunt
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
