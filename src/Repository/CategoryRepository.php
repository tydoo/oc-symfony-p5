<?php

namespace App\Repository;

use App\Entity\Category;
use Core\AbstractRepository;

/**
 * @extends AbstractRepository<Category>
 *
 * @method Category|null find($id)
 * @method Category|null findRandom()
 * @method Category|null findOneBy(array $criteria)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria)
 */
class CategoryRepository extends AbstractRepository {
}
