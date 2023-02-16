<?php

define('ROOT', dirname(__DIR__).DIRECTORY_SEPARATOR);
require_once ROOT.'config/config.php';

$mainController = new Controller\MainController();

$request = $_SERVER['REQUEST_URI'];
$url = explode('?' , $request);
switch ($url[0]) {
    case '/':
        $mainController->index($_POST);
        break;
    case '/blog':
        $mainController->blog();
        break;
    case '/post':
        $mainController->post($url[1]);
        break;
    case '/login':
        $mainController->login($_POST);
        break;
}
