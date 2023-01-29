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
        $postsRepository = new Repository\PostsRepository();
        $posts = $postsRepository->getAllPosts();
        echo $this->twig->render('pages/home.html.twig', [
            'posts' => $posts,
        ]);
    }

    public function blog()
    {
        echo $this->twig->render('pages/home.html.twig', ['posts' => 'This is a blog']);
    }
}
