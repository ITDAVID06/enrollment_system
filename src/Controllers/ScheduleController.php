<?php

namespace App\Controllers;

use App\Models\Program;
use App\Models\Section;
use App\Models\Course;
use App\Models\Schedule;
use App\Controllers\BaseController;

class ScheduleController extends BaseController
{
    public function showSchedule()
    {
        $data = [
            'isSchedule' => true,
        ];

        return $this->render('root', $data);
    }

    public function __construct()
    {
        $this->courseModel = new Course(); // Ensure the model is initialized
    }
    
    public function getCoursesByProgramAndSection($programId, $sectionId) {

            $semester = isset($_GET['semester']) ? $_GET['semester'] : '1st Sem';
            $scheduleModel = new Schedule();
            $semester = '1st Sem';
            $courses = $this->scheduleModel->getCoursesByProgramSectionAndSemester($programId, $sectionId, $semester);
          
            echo json_encode($courses);
      
    }
    

    public function getProgramSections(){
        try {
            $courseModel = new Schedule();
            $courses = $courseModel->getProgramSections();
            header('Content-Type: application/json');
            echo json_encode($courses);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Failed to fetch courses.']);
        }
    
    }

    public function deleteSchedules($courseId, $sectionId)
    {
        try {
            // Validate inputs
            if (!$courseId || !$sectionId) {
                header('Content-Type: application/json');
                http_response_code(400); // Bad Request
                echo json_encode(['error' => 'Invalid course or section ID']);
                return;
            }
    
            // Use the model to delete schedules
            $scheduleModel = new Schedule();
            $deleted = $scheduleModel->deleteSchedulesByCourseAndSection($courseId, $sectionId);
    
            // Return appropriate response
            if ($deleted > 0) {
                header('Content-Type: application/json');
                http_response_code(200); // OK
                echo json_encode(['message' => 'Schedules deleted successfully']);
            } else {
                header('Content-Type: application/json');
                http_response_code(404); // Not Found
                echo json_encode(['error' => 'No schedules found to delete']);
            }
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => 'An error occurred while deleting schedules: ' . $e->getMessage()]);
        }
    }
    

}
