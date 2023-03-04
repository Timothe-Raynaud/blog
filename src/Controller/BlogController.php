<?php

namespace Controller;

use Twig;
use Manager;
use Repository;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class BlogController
{
    private Twig\Environment $twig;
    private array $session;


    public function __construct()
    {
        $loader = new Twig\Loader\FilesystemLoader(ROOT.'/templates');
        $this->twig = new Twig\Environment($loader);
        $this->session = $_SESSION;
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
            'session' => $this->session,
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function post($id): void
    {
        $postsRepository = new Repository\PostsRepository();
        $post = $postsRepository->getPostById($id);

        echo $this->twig->render('front/pages/post.html.twig', [
            'post' => $post,
            'session' => $this->session,
        ]);
    }

}
