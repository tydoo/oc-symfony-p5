<?php

namespace App\Repository;

use App\Entity\Level;
use Core\AbstractRepository;

/**
 * @extends AbstractRepository<Level>
 *
 * @method Level|null find($id)
 * @method Level|null findRandom()
 * @method Level|null findOneBy(array $criteria)
 * @method Level[]    findAll()
 * @method Level[]    findBy(array $criteria)
 */
class LevelRepository extends AbstractRepository {
}
