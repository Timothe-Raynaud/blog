<?php

namespace Controller\Front;

define('ROOT', dirname(__DIR__));

use Manager\MailsManager;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class MainController
{
    private Environment $twig;
    private MailsManager $mailsManager;
    private array $session;

    public function __construct()
    {
        $loader = new FilesystemLoader(ROOT . '/templates');
        $this->twig = new Environment($loader);
        $this->mailsManager = new MailsManager();
        $this->session = $_SESSION;
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function index(?array $post = null): void
    {
        $mailResult = $this->mailsManager->sendMailToCreator($post);
        echo $this->twig->render('front/pages/home.html.twig', [
            'isSend' => $mailResult['isSend'],
            'message' => $mailResult['message'],
            'session' => $this->session,
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function error404(): void
    {
        echo $this->twig->render('front/pages/error_404.html.twig', [
        ]);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function sendMail(?array $post = null): void
    {
        echo json_encode($this->mailsManager->sendMailToCreator($post));
    }
}
