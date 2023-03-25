<?php

session_start();

require_once dirname(__DIR__) . '/config/config.php';

use Controller\Front\BlogController;
use Controller\Front\FrontController;
use Controller\Front\UserController;
use Controller\Back\BackUsersController;
use Controller\Back\BackPostsController;

// Front Controller
$frontController = new FrontController();
$blogController = new BlogController();
$userController = new UserController();

// Back Controller
$BackUsersController = new BackUsersController();
$BackPostsController = new BackPostsController();

// Get url information
$request = $_SERVER['REQUEST_URI'];
$url = explode('?', $request);

// rooting url
try {
    match ($url[0]) {
        // Main Controller
        '/' => $frontController->index($_POST),
        '/sendmail' => $frontController->sendMail($_POST),

        // Blog Controller
        '/blog' => $blogController->blog($url[1] ?? null),
        '/post' => $blogController->post($url[1]),
        '/create-post' => $blogController->createPost(),
        '/add-post' => $blogController->addPost($_POST),

        // User Controller
        '/reset' => $userController->renderResetPassword($url[1]),
        '/login' => $userController->login($url[1] ?? null),
        '/logout' => $userController->logout(),
        '/connexion' => $userController->connecting($_POST),
        '/my-account' => $userController->myAccount($url[1] ?? null),
        '/update-account' => $userController->updateAccount($_POST),
        '/update-password' => $userController->updatePassword($_POST),
        '/inscription' => $userController->signIn($_POST),
        '/reset-password' => $userController->resetPassword($_POST),
        '/is-login-exist' => $userController->isLoginExist($url[1]),
        '/is-username-exist' => $userController->isUsernameExist($url[1]),
        '/send-reset-password' => $userController->mailResetPassword($_POST),

        // BackUsers Controller
        '/admin-users' => $BackUsersController->users(),
        '/update-role' => $BackUsersController->updateRole($_POST),
        '/admin-user-validated' => $BackUsersController->userValidation($url[1]),
        '/admin-user-block' => $BackUsersController->userBlockation($url[1]),

        // BAckPosts Controller
        '/admin-posts' => $BackPostsController->posts(),

        // Default
        default => $frontController->error404(),
    };
} catch (\Twig\Error\LoaderError|\Twig\Error\RuntimeError|\Twig\Error\SyntaxError|Exception $exception) {
    throw new $exception;
}
