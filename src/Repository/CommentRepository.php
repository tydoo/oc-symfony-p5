<?php

namespace App\Repository;

use App\Entity\Comment;
use Core\AbstractRepository;

/**
 * @extends AbstractRepository<Comment>
 *
 * @method Comment|null find($id)
 * @method Comment|null findOneBy(array $criteria)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria)
 */
class CommentRepository extends AbstractRepository {
}
