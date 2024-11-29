<?php
namespace App\Controllers;

use App\Models\StudentRegistration;
use App\Controllers\BaseController;

class StudentRegistrationController extends BaseController
{
    public function showRegistrationForm()
    {
        $studentRegistration = new StudentRegistration ();
        $data = [
            'programs' => $studentRegistration->getPrograms(),
            'isRegistration' => true,
        ];

        return $this->render('studentroot', $data);
    }

    public function submitEnrollment()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            $studentRegistration = new StudentRegistration();
            $result = $studentRegistration->saveRegistration($data);

            if ($result['row_count'] > 0) {
                $_SESSION['success'] = 'Enrollment submitted successfully!';
            } else {
                $_SESSION['error'] = 'Failed to submit enrollment. Please try again.';
            }

            return $this->showRegistrationForm();  // Redirect back to the form view
        }
    }
}