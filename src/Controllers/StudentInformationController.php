<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\User;
use \PDO;
require 'vendor/autoload.php';

class StudentInformationController extends BaseController
{
    public function showStudentInfo()
    {
        $template = 'student-info';
        $data = [
            'title' => 'Student Information',
        ];
        $output = $this->render($template, $data);
        return $output;
    }

}