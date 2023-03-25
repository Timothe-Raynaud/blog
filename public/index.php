<?php

session_start();

require_once dirname(__DIR__) . '/config/config.php';

use Manager\RooterManager;

$rooter = new RooterManager();
$rooter->rooting();