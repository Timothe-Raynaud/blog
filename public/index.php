<?php

session_start();

define('ROOT', dirname(__DIR__));

require_once ROOT . '/config/config.php';

use Controller\Front\BlogController;
use Controller\Front\MainController;
use Controller\Front\UserController;
use Controller\Back\AdminController;

$mainController = new MainController();
$blogController = new BlogController();
$userController = new UserController();
$adminController = new AdminController();

$request = $_SERVER['REQUEST_URI'];
$url = explode('?', $request);

try {
    match ($url[0]) {
        // Main Controller
        '/' => $mainController->index($_POST),
        '/sendmail' => $mainController->sendMail($_POST),

        // Blog Controller
        '/blog' => $blogController->blog(),
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

        // Admin Controller
        '/admin-users' => $adminController->users(),
        '/admin-posts' => $adminController->posts(),
        '/update-role' => $adminController->updateRole($_POST),
        '/admin-user-validated' => $adminController->userValidation($url[1]),
        '/admin-user-block' => $adminController->userBlockation($url[1]),

        // Default
        default => $mainController->error404(),
    };
} catch (\Twig\Error\LoaderError $exception) {
    throw new $exception;
} catch (\Twig\Error\RuntimeError $exception) {
    throw new $exception;
} catch (\Twig\Error\SyntaxError $exception) {
    throw new $exception;
}
