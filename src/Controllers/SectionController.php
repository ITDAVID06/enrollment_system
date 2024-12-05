<?php

namespace App\Controllers;

use App\Models\Section;
use App\Models\Program;
use App\Models\Schedule;
use App\Models\Course;
use App\Controllers\BaseController;
use \PDO;

class SectionController extends BaseController
{

    public function showSections()
    {
        $sectionModel = new Section();
        $programModel = new Program();

        // Fetch all sections and programs
        $sections = $sectionModel->getAllSections();
        $programs = $programModel->getAllPrograms();    
      
        $data = [
            'isSection' => true,
            'sections' => $sections,
            'programs' => $programs, 
        ];
        
        return $this->render('root', $data);
    }

    public function listSections()
    {
        $model = new Section(); // Assuming a Section model exists
        echo json_encode($model->getSections());
    }

    public function getAllSections()
    {
        $sectionModel = new Section();
        $sections = $sectionModel->getAllSections();

        header('Content-Type: application/json');
        echo json_encode(['sections' => $sections]);
    }

    // Register a new section and create schedule entries for associated courses
    public function addSection()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
    
            $sectionModel = new Section();
    
            $result = $sectionModel->saveSection($data);
    
            if ($result['row_count'] > 0) {
                // Use the Section model's database connection to get the last inserted ID
                $sectionId = $result['last_insert_id'];
                
                echo json_encode(['success' => true, 'message' => 'Section and schedules added successfully!']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to add section.']);
            }
        }
    }
    
    // Get a specific section by ID
    public function getSection($id)
    {
        $sectionModel = new Section();
        echo json_encode($sectionModel->getSectionById($id));
    }

    // Update a section
    public function updateSection($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;

            $sectionModel = new Section();
            $result = $sectionModel->updateSection($id, $data);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Section updated successfully!']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to update section.']);
            }
        }
    }

    // Delete a section
    public function deleteSection($id)
    {
        $sectionModel = new Section();
        $sectionModel->deleteSection($id);
    }

}
