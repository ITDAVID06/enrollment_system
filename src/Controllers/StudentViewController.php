<?php
namespace App\Controllers;

// use App\Models\StudentInformation;
use App\Controllers\BaseController;

class StudentViewController extends BaseController
{
    public function showStudentViewSheet()
    { 
        // $studentInfo  = new StudentInformation();

        // Retrieve dropdown data
        $data = [
            // 'programs' => $studentInfo->getPrograms(),
            'isViewSheet' => true,
        ];

        return $this->render('studentroot', $data);
    }
}