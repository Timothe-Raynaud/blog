<?php

namespace Controller;

require_once ROOT.'/config/config.php';

use Twig;
use Manager;
use Repository;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class BlogController
{
    private Twig\Environment $twig;

    public function __construct()
    {
        $loader = new Twig\Loader\FilesystemLoader(ROOT.'/templates');
        $this->twig = new Twig\Environment($loader);
    }


    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function blog(): void
    {
        $postsRepository = new Repository\PostsRepository();
        $posts = $postsRepository->getAllPosts();
        echo $this->twig->render('front/pages/blog.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function post($id): void
    {
        // TODO faire en sorte que le contact soit récuperable dans le $post
        $postsRepository = new Repository\PostsRepository();
        $contactsRepository = new Repository\ContactRepository();
        $post = $postsRepository->getPostById($id);
        $createdBy = $contactsRepository->getContactsById($post['created_by']);

        echo $this->twig->render('front/pages/post.html.twig', [
            'post' => $post,
            'createdBy' => $createdBy,
        ]);
    }

}
