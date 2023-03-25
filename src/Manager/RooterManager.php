<?php

namespace Manager;

use Exception;
use Controller\Front\BlogController;
use Controller\Front\FrontController;
use Controller\Front\UserController;
use Controller\Back\BackUsersController;
use Controller\Back\BackPostsController;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class RooterManager
{
    // Front Controller
    private FrontController $frontController;
    private BlogController $blogController;
    private UserController $userController;

    // Back Controller
    private BackUsersController $BackUsersController;
    private BackPostsController $BackPostsController;

    public function __construct()
    {
        $this->frontController = new FrontController();
        $this->blogController = new BlogController();
        $this->userController = new UserController();
        $this->BackUsersController = new BackUsersController();
        $this->BackPostsController = new BackPostsController();
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function rooting()
    {
        // get url information
        $request = $_SERVER['REQUEST_URI'];
        $url = explode('?', $request);

        // rooting url
        try {
            match ($url[0]) {
                // Main Controller
                '/' => $this->frontController->index($_POST),
                '/sendmail' => $this->frontController->sendMail($_POST),

                // Blog Controller
                '/blog' => $this->blogController->blog($url[1] ?? null),
                '/post' => $this->blogController->post($url[1]),
                '/create-post' => $this->blogController->createPost(),
                '/add-post' => $this->blogController->addPost($_POST),

                // User Controller
                '/reset' => $this->userController->renderResetPassword($url[1]),
                '/login' => $this->userController->login($url[1] ?? null),
                '/logout' => $this->userController->logout(),
                '/connexion' => $this->userController->connecting($_POST),
                '/my-account' => $this->userController->myAccount($url[1] ?? null),
                '/update-account' => $this->userController->updateAccount($_POST),
                '/update-password' => $this->userController->updatePassword($_POST),
                '/inscription' => $this->userController->signIn($_POST),
                '/reset-password' => $this->userController->resetPassword($_POST),
                '/is-login-exist' => $this->userController->isLoginExist($url[1]),
                '/is-username-exist' => $this->userController->isUsernameExist($url[1]),
                '/send-reset-password' => $this->userController->mailResetPassword($_POST),

                // BackUsers Controller
                '/admin-users' => $this->BackUsersController->users(),
                '/update-role' => $this->BackUsersController->updateRole($_POST),
                '/admin-user-validated' => $this->BackUsersController->userValidation($url[1]),
                '/admin-user-block' => $this->BackUsersController->userBlockation($url[1]),

                // BAckPosts Controller
                '/admin-posts' => $this->BackPostsController->posts(),

                // Default
                default => $this->frontController->error404(),
            };
        } catch (LoaderError|RuntimeError|SyntaxError|Exception $exception) {
            throw new $exception;
        }
    }
}
