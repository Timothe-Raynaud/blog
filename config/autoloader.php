<?php

function loadClass($className): void
{
    $fileName = '';
    $namespace = '';

    $includePath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src';

    if (false !== ($lastNsPos = strripos($className, '\\'))) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
    $fullFileName = $includePath . DIRECTORY_SEPARATOR . $fileName;

    if (file_exists($fullFileName)) {
        require $fullFileName;
    } else if (DEV_ENVIRONMENT) {
        echo 'Class "' . $className . '" does not exist.';
    }
}

spl_autoload_register('loadClass');
