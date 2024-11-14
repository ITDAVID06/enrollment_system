<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\User;
use \PDO;
require 'vendor/autoload.php';

class HomeController extends BaseController
{
    public function index()
    {
        $template = 'home';
        $data = [
            'title' => 'Enrollment System',
        ];
        $output = $this->render($template, $data);
        return $output;
    }

}