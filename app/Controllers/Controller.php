<?php

namespace app\Controllers;

class Controller {
    protected function view($view, $data = []) {
        
        extract($data);

        $view = str_replace('.', '/', $view);
        $viewPath = "../app/Views/{$view}.php";

        if(file_exists($viewPath)) {
            ob_start(); 
            include($viewPath);
            $content = ob_get_clean();

            return $content;
        }

        return 'Ruta invalida: ' . $viewPath;
    }
}