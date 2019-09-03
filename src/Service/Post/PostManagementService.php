<?php

/*
 * This file is part of the "news-blog" package.
 *
 * (c) Degoda Anton <dehoda@ukr.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service\Post;

use App\Form\Dto\PostCreateDto;
use App\Form\Dto\CommentCreateDto;
use App\Mappers\PostMapper;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;
use App\Entity\User;
use App\Entity\Post;
use App\Entity\Comment;

class PostManagementService implements PostManagementServiceInterface
{
    private $postRepository;

    private $commentRepository;

    public function __construct(PostRepository $postRepository, CommentRepository $commentRepository)
    {
        $this->postRepository = $postRepository;
        $this->commentRepository = $commentRepository;
    }

    public function get(string $slug): Post
    {
        return $this->postRepository->findOneBy(['slug' => $slug]);
    }

    public function create(PostCreateDto $dto, User $user): void
    {
        $post = PostMapper::dtoToEntity($dto);
        $post->setAuthor($user);
        $this->postRepository->save($post);
    }

    public function edit(string $slug): PostCreateDto
    {
        $post = $this->get($slug);
        $dto = PostMapper::entityToDto($post);

        return $dto;
    }

    public function update(PostCreateDto $dto, string $slug): void
    {
        $post = $this->get($slug);
        $post = PostMapper::updateEntity($dto, $post);
        $this->postRepository->update();
    }

    public function delete(string $slug): void
    {
        $this->postRepository
            ->delete($this->get($slug));
    }

    public function getPosts($user): array
    {
        $posts = $this->postRepository->findBy(['author' => $user]);

        return $posts;
    }

    public function addComment(Post $post, CommentCreateDto $commentCreateDto): void
    {
        $comment = new Comment();
        $comment->setContent($commentCreateDto->content);
        $post->addComment($comment);
        $this->commentRepository->save($comment);
        $this->postRepository->update();
    }
}
