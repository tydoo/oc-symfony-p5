<?php

namespace App\Repository;

use App\Entity\User;
use Core\AbstractRepository;

/**
 * @extends AbstractRepository<User>
 *
 * @method User|null find($id)
 * @method User|null findRandom()
 * @method User|null findOneBy(array $criteria)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria)
 * @method int    countBy(array $criteria)
 */
class UserRepository extends AbstractRepository {
}
