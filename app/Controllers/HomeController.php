<?php 

namespace app\Controllers;

use app\Controllers\Controller;
use app\Models\Home;

class HomeController extends Controller{
    public function index() {
        $homeModel = new Home;
        $homeModel->delete(3);

        die();

        return $this->view('Home', ['title' => 'Welcome from the home page']);
    }
}