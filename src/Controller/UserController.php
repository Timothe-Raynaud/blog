<?php

namespace Controller;

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
    private array $session;


    public function __construct()
    {
        $loader = new FilesystemLoader(ROOT . '/templates');
        $this->twig = new Environment($loader);
        $this->userManager = new UserManager();
        $this->session = $_SESSION;
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function login(): void
    {
        echo $this->twig->render('front/pages/login.html.twig', [
            'session' => $this->session,
        ]);
    }


    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function signIn($post = null): void
    {
        $resultAddUser = $this->userManager->addNewUser($post);
        echo $this->twig->render('front/pages/login.html.twig', [
            'isAdd' => $resultAddUser['isAdd'],
            'message' => $resultAddUser['message'],
            'session' => $this->session,
        ]);
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function myAccount(): void
    {
        echo $this->twig->render('front/pages/account.html.twig', [
            'session' => $this->session,
        ]);
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function renderResetPassword($token): bool
    {
        $user = $this->userManager->getUserByResetToken($token);
        if($user) {
            echo $this->twig->render('front/pages/reset_password.html.twig', [
                'user' => $user,
                'token' => $token,
            ]);
            return true;
        }

        $message = 'Lien non valide';
        echo $this->twig->render('front/pages/home.html.twig', [
            'errorMessage' => $message,
        ]);
        return true;
    }

    public function logout(): void
    {
        $this->userManager->removeSession();
    }

    public function connecting($post = null): void
    {
        echo json_encode($this->userManager->connecting($post));
    }

    public function resetPassword($post = null): void
    {
        echo json_encode($this->userManager->resetPassword($post));
    }

    public function updateAccount($post = null): void
    {
        echo json_encode($this->userManager->updateAccount($post));
    }

    public function updatePassword($post = null): void
    {
        echo json_encode($this->userManager->updatePassword($post));
    }

    public function mailResetPassword($post = null): void
    {
        echo json_encode($this->userManager->sendMailResetPassword($post));
    }

    public function isLoginExist($login): void
    {
        echo json_encode($this->userManager->isLoginExist($login));
    }

    public function isUsernameExist($username): void
    {
        echo json_encode($this->userManager->isUsernameExist($username));
    }
}
