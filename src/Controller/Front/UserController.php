<?php

namespace Controller\Front;

define('ROOT', dirname(__DIR__));

use Manager\UserManager;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

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
    public function login(int $isError = null): void
    {
        $errorMessage = $this->userManager->askIfErrorAdmin($isError);

        echo $this->twig->render('front/pages/login.html.twig', [
            'session' => $this->session,
            'errorMessage' => $errorMessage,
        ]);
    }


    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function signIn(?array $post = null): void
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
    public function myAccount(int $isError = null): void
    {
        $errorMessage = $this->userManager->askIfErrorAdmin($isError);

        echo $this->twig->render('front/pages/account.html.twig', [
            'session' => $this->session,
            'errorMessage' => $errorMessage,
        ]);
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function renderResetPassword(string $token): bool
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

    public function connecting(?array $post = null): void
    {
        echo json_encode($this->userManager->connecting($post));
    }

    public function resetPassword(?array $post = null): void
    {
        echo json_encode($this->userManager->resetPassword($post));
    }

    public function updateAccount(?array $post = null): void
    {
        echo json_encode($this->userManager->updateAccount($post));
    }

    public function updatePassword(?array $post = null): void
    {
        echo json_encode($this->userManager->updatePassword($post));
    }

    public function mailResetPassword(?array $post = null): void
    {
        echo json_encode($this->userManager->sendMailResetPassword($post));
    }

    public function isLoginExist(string $login): void
    {
        echo json_encode($this->userManager->isLoginExist($login));
    }

    public function isUsernameExist(string $username): void
    {
        echo json_encode($this->userManager->isUsernameExist($username));
    }
}
