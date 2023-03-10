<?php

session_start();

require_once dirname(__DIR__) . '/config/config.php';

use Controller\MainController;
use Controller\BlogController;
use Controller\UserController;

$mainController = new MainController();
$blogController = new BlogController();
$userController = new UserController();
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

        // User Controller
        '/reset' => $userController->renderResetPassword($url[1]),
        '/login' => $userController->login(),
        '/logout' => $userController->logout(),
        '/connexion' => $userController->connecting($_POST),
        '/my-account' => $userController->myAccount(),
        '/inscription' => $userController->signIn($_POST),
        '/reset-password' => $userController->resetPassword($_POST),
        '/is-login-exist' => $userController->isLoginExist($url[1]),
        '/is-username-exist' => $userController->isUsernameExist($url[1]),
        '/send-reset-password' => $userController->mailResetPassword($_POST),

        // Default
        default => $mainController->error404(),
    };
} catch (Exception $exception) {
    var_dump($exception);
}
