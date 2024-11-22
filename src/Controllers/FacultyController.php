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

    public function list()
{
    $facultyModel = new Faculty();
    echo json_encode($facultyModel->getAllFaculty());
}

public function get($id)
{
    $facultyModel = new Faculty();
    echo json_encode($facultyModel->getFacultyById($id));
}

public function update($id)
{
    try {
        $data = $_POST;

        // Hash password if provided
        if (!empty($data['password'])) {
            $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $facultyModel = new Faculty();
        $result = $facultyModel->update($id, $data);

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


public function delete($id)
{
    $facultyModel = new Faculty();
    $facultyModel->delete($id);
}

}
