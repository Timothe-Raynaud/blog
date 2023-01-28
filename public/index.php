<?php

define('ROOT', dirname(__DIR__).DIRECTORY_SEPARATOR);

include ROOT.'config/autoloader.php';
require ROOT.'vendor/autoload.php';
require ROOT.'config/database.php';


$pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
$loader = new \Twig\Loader\FilesystemLoader(ROOT.'templates');
$twig = new \Twig\Environment($loader);
$mainController = new Controller\MainController($twig);
$request = $_SERVER['REQUEST_URI'];

switch ($request) {
    case '/':
        $mainController->index();
        break;
    case '/blog':
        $mainController->blog();
        break;
}
