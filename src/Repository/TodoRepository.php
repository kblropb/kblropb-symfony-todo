<?php

namespace App\Repository;

use App\Entity\Todo;

/**
 * Class TodoRepository
 *
 * @package App\Repository
 */
class TodoRepository extends AbstractRepository
{
    /**
     * @return string
     */
    static function getEntityClass(): string
    {
        return Todo::class;
    }
}
