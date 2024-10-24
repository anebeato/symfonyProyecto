<?php

namespace App\Repository;

use App\Entity\Curso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Curso>
 */
class CursoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Curso::class);
    }


    public function add(Curso $curso):void
    {
        $this->getEntityManager()->persist($curso);
        $this->getEntityManager()->flush();
    }

    public function remove(Curso $curso):void
    {
        $this->getEntityManager()->remove($curso);
        $this->getEntityManager()->flush();
    }

    public function findCursosSinNotaByAlumnoId(int $alumnoId): array
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.usucurso', 'uc')
            ->where('uc.alumno = :alumnoId')
            ->andWhere('uc.nota IS NULL')
            ->setParameter('alumnoId', $alumnoId)
            ->getQuery()
            ->getResult();
    }


    //    /**
    //     * @return Curso[] Returns an array of Curso objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Curso
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
