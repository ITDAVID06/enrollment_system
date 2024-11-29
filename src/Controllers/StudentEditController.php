<?php
namespace App\Controllers;

// use App\Models\StudentInformation;
use App\Controllers\BaseController;

class StudentEditController extends BaseController
{
    public function showStudentEditSheet()
    { 
        // $studentInfo  = new StudentInformation();

        // Retrieve dropdown data
        $data = [
            // 'programs' => $studentInfo->getPrograms(),
            'isEditSheet' => true,
        ];

        return $this->render('studentroot', $data);
    }
}