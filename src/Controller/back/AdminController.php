<?php

namespace Controller\back;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class AdminController
{
    private Environment $twig;
    private array $session;

    public function __construct()
    {
        $loader = new FilesystemLoader(ROOT . '/templates');
        $this->twig = new Environment($loader);
        $this->session = $_SESSION;
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function index(): void
    {
        if(!isset($this->session['role'])) {
            header("Location: login?1");
        } else if ($this->session['role'] != 'ADMIN') {
            header("Location: my-account?1");
        } else {
            echo $this->twig->render('back/pages/users.html.twig', [
                'session' => $this->session,
            ]);
        }
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function users(): void
    {
        if(!isset($this->session['role'])) {
            header("Location: login?1");
        } else if ($this->session['role'] != 'ADMIN') {
            header("Location: my-account?1");
        } else {
            echo $this->twig->render('back/pages/users.html.twig', [
                'session' => $this->session,
            ]);
        }
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function posts(): void
    {
        if(!isset($this->session['role'])) {
            header("Location: login?1");
        } else if ($this->session['role'] != 'ADMIN') {
            header("Location: my-account?1");
        } else {
            echo $this->twig->render('back/pages/posts.html.twig', [
                'session' => $this->session,
            ]);
        }
    }

}
