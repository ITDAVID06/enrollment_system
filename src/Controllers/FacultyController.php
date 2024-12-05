<?php

namespace App\Controllers;

use App\Models\Program;
use App\Models\Faculty;
use App\Controllers\BaseController;

class FacultyController extends BaseController
{
    // Display the form to add a new faculty
    public function showFaculty()
    {

        $programModel = new Program();
        $programs = $programModel->getAllPrograms();
        $data = [
            'isFaculty' => true,
            'programs' => $programs, // Pass programs to the template
            'complete_name' => $_SESSION['complete_name'] ?? '',
            'email' => $_SESSION['email'] ?? '',
        ];

        return $this->render('root', $data);
    }

    // Handle the form submission
    public function addFaculty()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Collect the data from the form
            $data = $_POST;

            // Create a new Faculty model instance
            $faculty = new Faculty();

            // Save the faculty data
            $result = $faculty->saveFaculty($data);

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

    public function listFaculty()
    {
        $facultyModel = new Faculty();
        echo json_encode($facultyModel->getAllFaculty());
    }

    public function getFaculty($id)
    {
        $facultyModel = new Faculty();
        echo json_encode($facultyModel->getFacultyById($id));
    }

    public function updateFaculty($id)
    {
        try {
            $data = $_POST;

            // Hash password if provided
            if (!empty($data['password'])) {
                $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
            }

            $facultyModel = new Faculty();
            $result = $facultyModel->updateFaculty($id, $data);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Faculty updated successfully!']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to update faculty.']);
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function deleteFaculty($id)
    {
        $facultyModel = new Faculty();
        $facultyModel->deleteFaculty($id);
    }

    public function updateProfile() {
        $this->initializeSession();

        $data = json_decode(file_get_contents("php://input"), true);

        $email = $data['email'];
        $password = $data['password'];
        $facultyId = $_SESSION['user_id'];

        $facultyModel = new Faculty();

        if ($facultyModel->isEmailTaken($email, $facultyId)) {
            http_response_code(400);
            echo json_encode(['message' => 'Email is already taken.']);
            return;
        }

        $updateData = ['email' => $email];
        if (!empty($password)) {
            $updateData['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($facultyModel->updateFaculty($facultyId, $updateData)) {
            http_response_code(200);
            echo json_encode(['message' => 'Profile updated successfully.']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Failed to update profile.']);
        }
    }


}
