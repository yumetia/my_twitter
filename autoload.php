<?php

spl_autoload_register(function ($class) {

    $directories = [
        __DIR__ . '/Controller/',
        __DIR__ . '/Model/',
        __DIR__ . '/Helper/',
    ];

    foreach ($directories as $directory) {
        $file = $directory . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }

    throw new Exception("Impossible de charger la classe : $class");
});
