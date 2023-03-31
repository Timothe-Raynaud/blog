<?php

class Autoloader
{
    /**
     * @throws Exception
     */
    public static function loadClass(string $className): void
    {
        try {
            $fileName = '';
            $namespace = '';

            $includePath = dirname(__DIR__) . '/';

            if (false !== ($lastNsPos = strripos($className, '\\'))) {
                $namespace = substr($className, 0, $lastNsPos);
                $className = substr($className, $lastNsPos + 1);
                $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
            }
            $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
            $fullFileName = $includePath . DIRECTORY_SEPARATOR . $fileName;

            require $fullFileName;
        } catch (Exception $exception) {
            throw new Exception($exception);
        }
    }
}

