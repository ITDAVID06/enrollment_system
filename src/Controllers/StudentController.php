<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class StudentController extends BaseController
{
    // Default method for showing the dashboard
    public function showStudentProfile(){
        $data = [
            'isProfile' => false,   // Default to dashboard
            'isRegistration' => false,
            'isViewSheet' => false,
            'isEditSheet' => false,
            'isSchedule' => false,
            'isAccount' => false,
        ];
        return $this->render('studentroot', $data);
    }
}