<?php

declare(strict_types=1);

// Simple autoloader for testing without Composer
spl_autoload_register(function (string $className): void {
    // Convert namespace to file path
    $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $className);

    // Remove the root namespace part
    if (str_starts_with($classPath, 'Ykw' . DIRECTORY_SEPARATOR . 'Cruet' . DIRECTORY_SEPARATOR)) {
        $classPath = substr($classPath, strlen('Ykw' . DIRECTORY_SEPARATOR . 'Cruet' . DIRECTORY_SEPARATOR));
    }

    $filePath = __DIR__ . '/../src/' . $classPath . '.php';

    if (file_exists($filePath)) {
        require_once $filePath;
    }
});
