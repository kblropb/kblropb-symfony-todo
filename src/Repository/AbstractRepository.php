<?php

namespace App\Repository;

use App\Entity\EntityInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\{OptimisticLockException, ORMException};
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class BaseRepository
 *
 * @package App\Repository
 */
abstract class AbstractRepository extends ServiceEntityRepository
{
    /**
     * @return string
     */
    abstract static function getEntityClass(): string;

    /**
     * BaseRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, static::getEntityClass());
    }

    /**
     * @param EntityInterface $entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function saveEntity(EntityInterface $entity): void
    {
        $this->_em->persist($entity);
        $this->_em->flush();
    }

    /**
     * @param EntityInterface $entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function deleteEntity(EntityInterface $entity): void
    {
        $this->_em->remove($entity);
        $this->_em->flush();
    }
}
