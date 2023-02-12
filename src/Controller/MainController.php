<?php

namespace Controller;

require_once ROOT.'config/config.php';

use Repository;

class MainController
{
    private \Twig\Environment $twig;

    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader(ROOT.'templates');
        $twig = new \Twig\Environment($loader);
        $this->twig = $twig;
    }

    public function index()
    {
        echo $this->twig->render('front/pages/home.html.twig', [
        ]);
    }

    public function blog()
    {
        echo $this->twig->render('front/pages/blog.html.twig', [
        ]);
    }

    public function post()
    {
        $postsRepository = new Repository\PostsRepository();
        $posts = $postsRepository->getAllPosts();
        echo $this->twig->render('front/pages/blog.html.twig', [
            'posts' => $posts,
        ]);
    }

    public function login()
    {
        echo $this->twig->render('front/pages/login.html.twig', [
        ]);
    }
}
