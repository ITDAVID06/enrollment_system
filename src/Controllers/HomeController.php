<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class HomeController extends BaseController
{
    public function index()
    {
        $template = 'root';
        $data = [
            'title' => 'Enrollment System',
        ];
        $output = $this->render($template, $data);
        return $output;
    }
}