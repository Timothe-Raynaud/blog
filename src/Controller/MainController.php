<?php

namespace Controller;

require_once ROOT.'/config/config.php';

use Manager\MailsManager;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MainController
{
    private Environment $twig;
    private MailsManager $mailsManager;

    public function __construct()
    {
        $loader = new FilesystemLoader(ROOT.'/templates');
        $this->twig = new Environment($loader);
        $this->mailsManager = new MailsManager();
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function index($post = null): void
    {
        $mailResult = $this->mailsManager->sendMailToCreator($post);
        echo $this->twig->render('front/pages/home.html.twig', [
            'isSend' => $mailResult['isSend'],
            'message' => $mailResult['message'],
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
