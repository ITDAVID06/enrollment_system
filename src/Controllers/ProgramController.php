<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class ProgramController extends BaseController
{
    public function showProgram(){
        $template = 'root';
        $data = [
            'isProgram' => true,
            'isDashboard' => false,
            'isStudent' => false,
            'isFaculty' => false,
            'isSection' => false,
            'isProfile' => false,
            'isCourse' => false,
        ];
        return $this->render($template, $data);
    }
}

