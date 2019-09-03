<?php

/*
 * This file is part of the "news-blog" package.
 *
 * (c) Degoda Anton <dehoda@ukr.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service\HomePage;

use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;

class HomePageService implements HomePageServiceInterface
{
    private $postRepository;

    private $paginator;

    public function __construct(PostRepository $postRepository, PaginatorInterface $paginator)
    {
        $this->postRepository = $postRepository;
        $this->paginator = $paginator;
    }

    public function getPosts(int $page)
    {
        $query = $this->postRepository
            ->createQueryBuilder('p')
            ->getQuery();
        $posts = $this->paginator->paginate(
            $query,
            $page,
            5
        );

        return $posts;
    }
}
