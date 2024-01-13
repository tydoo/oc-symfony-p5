<?php

namespace App\Repository;

use App\Entity\BlogPost;
use Core\AbstractRepository;

/**
 * @extends AbstractRepository<BlogPost>
 *
 * @method BlogPost|null find($id)
 * @method BlogPost|null findRandom()
 * @method BlogPost|null findOneBy(array $criteria)
 * @method BlogPost[]    findAll()
 * @method BlogPost[]    findBy(array $criteria)
 * @method int    countBy(array $criteria)
 */
class BlogPostRepository extends AbstractRepository {
}
