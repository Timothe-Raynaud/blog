<?php

namespace Controller\Back;

use Manager\UserManager;
use Repository\UserRepository;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class AdminController
{
    private Environment $twig;
    private UserManager $userManager;
    private UserRepository $userRepository;
    private array $session;

    public function __construct()
    {
        $loader = new FilesystemLoader(ROOT . '/templates');
        $this->twig = new Environment($loader);
        $this->session = $_SESSION;
        $this->userManager = new UserManager();
        $this->userRepository = new UserRepository();
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function users(): void
    {
        if (!isset($this->session['role'])) {
            header("Location: login?1");
        } else if ($this->session['role'] != 'ADMIN') {
            header("Location: my-account?1");
        } else {
            $users = $this->userRepository->getAllUsers();

            echo $this->twig->render('back/pages/users.html.twig', [
                'session' => $this->session,
                'users' => $users,
            ]);
        }
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function userValidation($user_id): void
    {
        if (!isset($this->session['role'])) {
            header("Location: login?1");
        } else if ($this->session['role'] != 'ADMIN') {
            header("Location: my-account?1");
        } else {
            $this->userRepository->setIsAvailable($user_id);
            $users = $this->userRepository->getAllUsers();

            echo $this->twig->render('back/pages/users.html.twig', [
                'session' => $this->session,
                'users' => $users,
            ]);
        }
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function userBlockation($user_id): void
    {
        if (!isset($this->session['role'])) {
            header("Location: login?1");
        } else if ($this->session['role'] != 'ADMIN') {
            header("Location: my-account?1");
        } else {
            $this->userRepository->setIsNotAvailable($user_id);
            $users = $this->userRepository->getAllUsers();

            echo $this->twig->render('back/pages/users.html.twig', [
                'session' => $this->session,
                'users' => $users,
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
        if (!isset($this->session['role'])) {
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
