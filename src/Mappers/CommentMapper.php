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

use App\Form\Dto\CommentCreateDto;

class CommentMapper
{
    public static function dtoToEntity(CommentCreateDto $dto): string
    {
        return $dto->content;
    }
}
