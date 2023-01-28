<?php

namespace Controller;

use Manager\PostsManager;
use Twig\Environment;

class MainController
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index()
    {
        echo $this->twig->render('content/pages/home.html.twig', ['posts' => 'allPosts']);
    }

    public function blog()
    {
        echo $this->twig->render('content/pages/home.html.twig', ['posts' => 'This is a blog']);
    }
}