<?php

/*
 * This file is part of the "news-blog" package.
 *
 * (c) Degoda Anton <dehoda@ukr.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Mappers;

use App\Entity\Post;
use App\Form\Dto\PostCreateDto;

class PostMapper
{
    public static function dtoToEntity(PostCreateDto $dto): Post
    {
        $entity = new Post();
        $entity
            ->setTitle($dto->title)
            ->setContent($dto->content);

        return $entity;
    }
    public static function updateEntity(PostCreateDto $dto, Post $entity): Post
    {
        $entity
            ->setTitle($dto->title)
            ->setContent($dto->content);

        return $entity;
    }
    public static function entityToDto(Post $post): PostCreateDto
    {
        $dto = new PostCreateDto();
        $dto->title = $post->getTitle();
        $dto->content = $post->getContent();

        return $dto;
    }
}
