<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function showDashboard(){
        $template = 'root';
        $data = [
            'isDashboard' => true,
            'isStudent' => false,
            'isFaculty' => false,
            'isSection' => false,
            'isProfile' => false,
            'isCourse' => false,
            'isProgram' => false,
        ];
        return $this->render($template, $data);
    }
}