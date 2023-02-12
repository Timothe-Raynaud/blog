<?php

define('ROOT', dirname(__DIR__).DIRECTORY_SEPARATOR);
require_once ROOT.'config/config.php';

$mainController = new Controller\MainController();

$request = $_SERVER['REQUEST_URI'];
switch ($request) {
    case '/':
        $mainController->index();
        break;
    case '/blog':
        $mainController->blog();
        break;
    case '/post':
        $mainController->post();
        break;
    case '/login':
        $mainController->login();
        break;
}
