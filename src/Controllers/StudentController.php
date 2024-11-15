<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class StudentController extends BaseController
{
    // Default method for showing the dashboard
    public function showStudentProfile(){
        $data = [
            'isProfile' => true,   // Default to dashboard
            'isRegistration' => false,
            'isSchedule' => false,
            'isAccount' => false,
        ];
        return $this->render('studentroot', $data);
    }
}