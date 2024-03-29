<?php

namespace Controller\Back;

use Config\Config;
use Exception;
use Entity\Post;
use Repository\PostsRepository;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class BackPostsController
{
    private Environment $twig;
    private PostsRepository $postsRepository;

    private array $session;

    public function __construct()
    {
        $loader = new FilesystemLoader(Config::$ROOT  . '/templates');
        $this->twig = new Environment($loader);
        $this->session = $_SESSION;
        $this->postsRepository = new PostsRepository();
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function posts(): void
    {
        if (!isset($this->session['role'])) {
            header("Location: login?1");
        } else if ($this->session['role'] != 'ADMIN') {
            header("Location: my-account?1");
        } else {
            $validatedPosts = $this->postsRepository->countValidatedPosts();
            $posts = $this->postsRepository->getAllPosts();

            echo $this->twig->render('back/pages/posts.html.twig', [
                'session' => $this->session,
                'posts' => $posts,
                'validatedPosts' => $validatedPosts,
            ]);
        }
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function postValidation(int $post_id): void
    {
        if (!isset($this->session['role'])) {
            header("Location: login?1");
        } else if ($this->session['role'] != 'ADMIN') {
            header("Location: my-account?1");
        } else {
            $this->postsRepository->setIsAvailable($post_id);

            $this->posts();
        }
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function postBlockation(int $post_id): void
    {
        if (!isset($this->session['role'])) {
            header("Location: login?1");
        } else if ($this->session['role'] != 'ADMIN') {
            header("Location: my-account?1");
        } else {
            $this->postsRepository->setIsNotAvailable($post_id);

            $this->posts();
        }
    }

}
