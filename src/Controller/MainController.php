<?php

namespace Controller;

use Repository\PostsRepository;
use Manager\PostsManager;
use Twig\Environment;

class MainController
{
    private $twig;
    private $postsRepository;
    private $postManager;

    public function __construct(Environment $twig, PostsRepository $postsRepository, PostsManager $postManager)
    {
        $this->twig = $twig;
        $this->postsRepository = $postsRepository;
        $this->postManager = $postManager;
    }

    public function index()
    {
        echo $this->twig->render('content/front/pages/home.html.twig', ['posts' => 'allPosts']);
    }

    public function blog()
    {
        echo $this->twig->render('content/front/pages/home.html.twig', ['posts' => 'This is a blog']);
    }
}