<?php

namespace App\Repository;

use App\Entity\Usucurso;
use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Usucurso>
 */
class UsucursoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Usucurso::class);
    }

    public function findUsersByCursoId(int $cursoId): array
    {
        return $this->createQueryBuilder('u')
            ->addSelect('usua')
            ->join('u.id_usuario', 'usua')
            ->andWhere('u.id_curso = :cursoId')
            ->setParameter('cursoId', $cursoId)
            ->getQuery()
            ->getResult();
    }

    public function add(Usucurso $usucurso): void
    {
        $this->getEntityManager()->persist($usucurso);
        $this->getEntityManager()->flush();
    }

    public function findOneByUsuarioAndCurso(int $usuarioId, int $cursoId): ?Usucurso
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.id_usuario = :usuarioId')
            ->andWhere('u.id_curso = :cursoId')
            ->setParameter('usuarioId', $usuarioId)
            ->setParameter('cursoId', $cursoId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    //    /**
    //     * @return Usucurso[] Returns an array of Usucurso objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Usucurso
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}