<?php

/*
 * This file is part of the "news-blog" package.
 *
 * (c) Degoda Anton <dehoda@ukr.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method null|Post find($id, $lockMode = null, $lockVersion = null)
 * @method null|Post findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findAllPaginated(int $page, int $limit)
    {
        $qb = $this->createQueryBuilder('p');

        return $qb
            ->addOrderBy('p.updatedAt', 'DESC')
            ->setFirstResult(1 === $page ? 0 : ($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }
    public function save(Post $post): void
    {
        $em = $this->getEntityManager();
        $em->persist($post);
        $em->flush();
    }
    public function update(): void
    {
        $em = $this->getEntityManager();
        $em->flush();
    }
    public function delete(Post $post)
    {
        $em = $this->getEntityManager();
        $em->remove($post);
        $em->flush();
    }
}
