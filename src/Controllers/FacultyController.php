<?php

namespace App\Controllers;

use App\Models\Faculty;
use App\Controllers\BaseController;

class FacultyController extends BaseController
{
    // Display the form to add a new faculty
    public function showFaculty()
    {
        $data = [
            'isDashboard' => false, 
            'isStudent' => false,
            'isFaculty' => true,
            'isSection' => false,
            'isProfile' => false,
            'isCourse' => false,
            'isProgram' => false,
        ];
        return $this->render('root', $data);
    }

    // Handle the form submission
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Collect the data from the form
            $data = $_POST;

            // Create a new Faculty model instance
            $faculty = new Faculty();

            // Save the faculty data
            $result = $faculty->save($data);

            // Check if the insertion was successful
            if ($result['row_count'] > 0) {
                $_SESSION['success'] = 'Faculty registered successfully!';

                $templateData = [
                    'isFaculty' => true,
                ];

                return $this->render('root', $templateData); 
            } else {
                $_SESSION['error'] = 'Failed to register faculty. Please try again.';
                $templateData = [
                    'isFaculty' => true,
                ];
                return $this->render('root', $templateData); 
            }
        }
    }
}
