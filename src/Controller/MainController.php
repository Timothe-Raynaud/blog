<?php

namespace Controller;

require_once ROOT.'/config/config.php';

use Twig;
use Manager;
use Repository;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MainController
{
    private Twig\Environment $twig;
    private Manager\MailsManager $mailer;

    public function __construct()
    {
        $loader = new Twig\Loader\FilesystemLoader(ROOT.'/templates');
        $this->twig = new Twig\Environment($loader);
        $this->mailer = new Manager\MailsManager();
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function index($post = null): void
    {
        $data = $this->mailer->sendMailToCreator($post);
        echo $this->twig->render('front/pages/home.html.twig', [
            'data' => $data,
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function login(): void
    {
        echo $this->twig->render('front/pages/login.html.twig', [
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function error404(): void
    {
        echo $this->twig->render('layouts/error404.html.twig', [
        ]);
    }
}
