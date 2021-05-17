<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\{OptimisticLockException, ORMException};
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\{PasswordUpgraderInterface, UserInterface};

/**
 * Class UserRepository
 *
 * @package App\Repository
 */
class UserRepository extends AbstractRepository implements PasswordUpgraderInterface
{
    /**
     * @return string
     */
    static function getEntityClass(): string
    {
        return User::class;
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     *
     * @param UserInterface $user
     * @param string $newEncodedPassword
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->saveEntity($user);
    }
}
