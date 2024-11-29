<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Registration;


class RegistrationController extends BaseController
{
    public function showRegistration()
    {
        $registration = new Registration ();
        $data = [
            'programs' => $registration->getPrograms(),
        ];
        return $this->render('registration', $data);
    }

    public function submitEnrollment()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST; // Get form data
    
            // Handle file upload
            if (isset($_FILES['uploaded_grade_file']) && $_FILES['uploaded_grade_file']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../uploads/grades/';
                $uploadUrl = '/uploads/grades/';
                $fileName = uniqid() . '_' . basename($_FILES['uploaded_grade_file']['name']);
                $filePath = $uploadDir . $fileName;
    
                // Ensure the upload directory exists
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
    
                // Move the uploaded file to the upload directory
                if (move_uploaded_file($_FILES['uploaded_grade_file']['tmp_name'], $filePath)) {
                    // Save the relative URL of the uploaded file
                    $data['uploaded_grade_file'] = $uploadUrl . $fileName;
                } else {
                    $_SESSION['error'] = 'Failed to upload grade file. Please try again.';
                    header('Location: /registration');
                    exit;
                }
            } else {
                $_SESSION['error'] = 'Please upload a valid grade file.';
                header('Location: /registration');
                exit;
            }
    
            // Save enrollment data
            $registration = new Registration();
            $result = $registration->saveRegistration($data);
    
            if ($result['row_count'] > 0) {
                $_SESSION['success'] = 'Enrollment submitted successfully!';
                header('Location: /success');
            } else {
                $_SESSION['error'] = 'Failed to submit enrollment. Please try again.';
                header('Location: /registration');
            }
    
            exit;
        }
    }

    public function success()
    {
        if (isset($_SESSION['success'])) {
            $message = $_SESSION['success'];
            unset($_SESSION['success']); // Remove the message after displaying
            return $this->render('success', ['message' => $message]);
        } else {
            header('Location: /'); // Redirect to home if no success message exists
            exit;
        }
    }

    public function checkEmail()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $email = $input['email'] ?? null;

            if ($email) {
                $exists = $this->emailExists($email);
                echo json_encode(['exists' => $exists ? true : false]);
            } else {
                echo json_encode(['exists' => false]);
            }
        }
    }
}