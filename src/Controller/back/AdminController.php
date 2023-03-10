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
            echo $this->twig->render('back/pages/login.html.twig', [

            ]);
        } else if ($this->session['role'] === 'ADMIN') {
            echo $this->twig->render('back/pages/home.html.twig', [
                'session' => $this->session,
            ]);
        } else {
            echo $this->twig->render('back/pages/access_denied.html.twig', [

            ]);
        }
    }

}
