<?php

namespace App\Controllers;

use App\Models\Enrollees;
use App\Models\Program;
use App\Controllers\BaseController;

class EnrolleesController extends BaseController
{
    public function showEnrollees()
    {
        $data = [
            'isDashboard' => false,
            'isStudent' => false,
            'isFaculty' => false,
            'isSection' => false,
            'isProfile' => false,
            'isCourse' => false,
            'isProgram' => false,
            'isEnrollees' => true,
        ];

        return $this->render('root', $data);
    }

    public function listEnrollees()
    {
        $enrolleesModel = new Enrollees();
        $enrollees = $enrolleesModel->getAllEnrolleesWithDetails();
        header('Content-Type: application/json');
        echo json_encode($enrollees);
    }

    public function listPrograms()
    {
        $programsModel = new Program(); // Assume Programs is a model for the programs table
        $programs = $programsModel->getAllPrograms();
        header('Content-Type: application/json');
        echo json_encode($programs);
    }


    public function getEnrollees($id)
    {
        $enrolleesModel = new Enrollees();
        $enrollee = $enrolleesModel->getEnrolleeById($id);
        echo json_encode($enrollee);
    }

    public function updateEnrollee($id)
    {
        try {
            $data = $_POST;

            $enrolleesModel = new Enrollees();
            $result = $enrolleesModel->updateEnrollee($id, $data);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Enrollee updated successfully!']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to update enrollee.']);
            }
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}