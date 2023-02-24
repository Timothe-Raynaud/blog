<?php

namespace Controller;

require_once ROOT.'/config/config.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Manager\UserManager;

class UserController
{
    private Environment $twig;
    private UserManager $userManager;

    public function __construct()
    {
        $loader = new FilesystemLoader(ROOT.'/templates');
        $this->twig = new Environment($loader);
        $this->userManager = new UserManager();
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
    public function signIn($post = null): void
    {
        $resultAddUser = $this->userManager->addNewUser($post);
        echo $this->twig->render('front/pages/login.html.twig', [
            'isAdd' => $resultAddUser['isAdd'],
            'message' => $resultAddUser['message'],
        ]);
    }

    public function isLoginExist($login) : void
    {
        $this->userManager->isLoginExist($login);
    }

    public function isUsernameExist($username) : void
    {
        $this->userManager->isUsernameExist($username);
    }
}
