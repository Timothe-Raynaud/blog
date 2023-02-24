<?php

define('ROOT', dirname(__DIR__));
require_once ROOT.'/config/config.php';
use Controller\MainController;
use Controller\BlogController;
use Controller\UserController;

$mainController = new MainController();
$blogController = new BlogController();
$userController = new UserController();
$request = $_SERVER['REQUEST_URI'];
$url = explode('?' , $request);

try {
    match ($url[0]) {
        '/' => $mainController->index($_POST),
        '/blog' => $blogController->blog(),
        '/post' => $blogController->post($url[1]),
        '/login' => $userController->login(),
        '/sign-in' => $userController->signIn($_POST),
        '/is-login-exist' => $userController->isLoginExist($url[1]),
        '/is-username-exist' => $userController->isUsernameExist($url[1]),
        default => $mainController->error404(),
    };
} catch (Exception $e) {
    if(DEV_ENVIRONMENT){
        var_dump($e);
    }
}
