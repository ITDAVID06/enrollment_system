<?php

namespace App\Controllers;

use App\Models\Program;
use App\Controllers\BaseController;

class ProgramController extends BaseController
{
    public function showProgram()
    {
        $data = [
            'isDashboard' => false,
            'isStudent' => false,
            'isFaculty' => false,
            'isSection' => false,
            'isProfile' => false,
            'isCourse' => false,
            'isProgram' => true,
        ];

        return $this->render('root', $data);
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;

            $program = new Program();

            $result = $program->save($data);

            if ($result['row_count'] > 0) {
                $_SESSION['success'] = 'Program registered successfully!';

                $templateData = [
                    'isProgram' => true,
                ];

                return $this->render('root', $templateData);
            } else {
                $_SESSION['error'] = 'Failed to register program. Please try again.';
                $templateData = [
                    'isProgram' => true,
                ];
                return $this->render('root', $templateData);
            }
        }
    }


    public function list()
    {
        $programModel = new Program();
        echo json_encode($programModel->getAllPrograms());
    }

    public function get($id)
    {
        $programModel = new Program();
        echo json_encode($programModel->getProgramById($id));
    }

    public function update($id)
    {
        try {
            $data = $_POST;

            $programModel = new Program();
            $result = $programModel->update($id, $data);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Program updated successfully!']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to update program.']);
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        $programModel = new Program();
        $programModel->delete($id);
    }
}
