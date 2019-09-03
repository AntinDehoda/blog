<?php

/*
 * This file is part of the "news-blog" package.
 *
 * (c) Degoda Anton <dehoda@ukr.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Post\PostManagementServiceInterface;
use App\Form\PostCreateType;
use App\Form\CommentCreateType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class PostController extends AbstractController
{
    /**
     * @Route("/post/{slug}", name="post_view")
     */
    public function view(Request $request, string $slug, PostManagementServiceInterface $postManagement): Response
    {
        $post = $postManagement->get($slug);

        if (null == $post) {
            throw $this->createNotFoundException('Post not found');
        }
        $form = $this->createForm(CommentCreateType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postManagement->addComment($post, $form->getData());

            $this->addFlash('success', 'Your comment was successfully added!');

            return $this->redirectToRoute('post_view', ['slug' => $slug]);
        }

        return $this->render('post/view.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @isGranted("ROLE_USER")
     * @Route("/profile/post/create", name="app_post_create")
     */
    public function create(Request $request, PostManagementServiceInterface $postManagement)
    {
        $form = $this->createForm(PostCreateType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postManagement->create($form->getData(), $this->getUser());

            $this->addFlash('success', 'Post was successfully created!');

            return $this->redirectToRoute('app_post_create');
        }

        return $this->render('post/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @isGranted("ROLE_USER")
     * @Route("/profile/post/{slug}/edit", name="app_post_edit")
     */
    public function edit(Request $request, PostManagementServiceInterface $postManagement, string $slug)
    {
        $dto = $postManagement->edit($slug);
        $form = $this->createForm(PostCreateType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postManagement->update($form->getData(), $slug);

            $this->addFlash('success', 'Post was successfully updated!');

            return $this->redirectToRoute('app_post_edit', [
                'slug' => $slug,
            ]);
        }

        return $this->render('post/edit.html.twig', [
            'postEditForm' => $form->createView(),
        ]);
    }

    /**
     * @isGranted("ROLE_USER")
     * @Route("/profile/post/{slug}/delete", name="app_post_delete")
     */
    public function delete(PostManagementServiceInterface $postManagement, string $slug)
    {
        $postManagement->delete($slug);

        return $this->redirectToRoute('view_posts');
    }

    /**
     * @isGranted("ROLE_USER")
     * @Route("/profile/posts", name="view_posts")
     */
    public function index()
    {
        $posts = $this->getUser()->getPosts();

        return $this->render('post/index.html.twig', [
            'posts' => $posts,

        ]);
    }
}
