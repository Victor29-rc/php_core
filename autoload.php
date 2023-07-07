<?php

spl_autoload_register(function($class) {
    $filePath = '../'.str_replace('\\', '/', $class) . '.php';

    if(file_exists($filePath)) {
        require_once($filePath);
    } else {
        die('File not found'. $filePath);
    }
});