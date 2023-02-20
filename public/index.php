<?php

define('ROOT', dirname(__DIR__));
require_once ROOT.'/config/config.php';

$mainController = new Controller\MainController();
$blogController = new Controller\BlogController();
$request = $_SERVER['REQUEST_URI'];
$url = explode('?' , $request);

try {
    match ($url[0]) {
        '/' => $mainController->index($_POST),
        '/blog' => $blogController->blog(),
        '/post' => $blogController->post($url[1]),
        '/login' => $mainController->login(),
        default => $mainController->error404(),
    };
} catch (Exception $e) {
    if(DEV_ENVIRONMENT){
        var_dump($e);
    }
}
