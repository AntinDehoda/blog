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

use App\Entity\User;
use App\Entity\Post;
use App\Form\Dto\PostCreateDto;
use App\Form\Dto\CommentCreateDto;

interface PostManagementServiceInterface
{
    public function get(string $slug): Post;
    public function create(PostCreateDto $dto, User $user): void;
    public function edit(string $slug): PostCreateDto;
    public function update(PostCreateDto $dto, string $slug): void;
    public function delete(string $slug): void;
    public function getPosts($user): array;
    public function addComment(Post $post, CommentCreateDto $commentCreateDto): void;
}
