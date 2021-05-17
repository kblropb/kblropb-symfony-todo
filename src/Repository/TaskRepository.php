<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class TaskRepository
 *
 * @package App\Repository
 */
class TaskRepository extends AbstractRepository
{
    /**
     * @return string
     */
    static function getEntityClass(): string
    {
        return Task::class;
    }

    /**
     * @param int $userId
     * @param int $listId
     *
     * @return int|mixed|string
     */
    public function findUserTasksByTodoId(int $userId, int $listId)
    {
        return $this->createQueryBuilder('t')
            ->select('t.id, t.name, t.is_done')
            ->leftJoin('t.todo', 'todo')
            ->andWhere('todo.user = :userId')
            ->andWhere('todo.id = :listId')
            ->setParameter('userId', $userId)
            ->setParameter('listId', $listId)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $userId
     * @param int $listId
     * @param int $taskId
     *
     * @return int|mixed|string|null
     * @throws NonUniqueResultException
     */
    public function findUserTaskByTodoIdAndTaskId(int $userId, int $listId, int $taskId)
    {
        $builder = $this->createQueryBuilder('t')
            ->leftJoin('t.todo', 'todo')
            ->andWhere('todo.user = :userId')
            ->andWhere('todo.id = :listId')
            ->andWhere('t.id = :taskId')
            ->setParameter('userId', $userId)
            ->setParameter('listId', $listId)
            ->setParameter('taskId', $taskId);

        return $builder->orderBy('t.id', 'DESC')
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }

    public function findUserTasksByCriteria(int $userId, array $filters)
    {
        $builder = $this
            ->createQueryBuilder('t')
            ->select('t.id, t.name, t.is_done,  todo.name as todoName')
            ->leftJoin('t.todo', 'todo');

        $params = [
            'userId' => $userId,
        ];

        if (isset($filters['name'])) {
            $builder->andWhere(
                $builder->expr()->like(' t.name ', ':name')
            );
            $params['name'] = '%' . $filters['name'] . '%';
        }

        if (isset($filters['is_done'])) {
            $builder->andWhere('t.is_done = :is_done');
            $params['is_done'] = $filters['is_done'];
        }

        $builder->andWhere('todo.user = :userId')
            ->setParameters($params);

        return $builder->getQuery()->getResult();
    }
}
