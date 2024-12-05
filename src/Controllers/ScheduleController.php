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
     
    public function getProgramSections()
    {
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

    public function getScheduleBySection($section_id)
    {
        try {

            $courseModel = new Course(); // Assuming the model is named `Course`
            
            // Call the model method to get courses for the section
            $courses = $courseModel->getCourseSchedule($section_id);
            
            // Check if the query returned results
            if ($courses === false) {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to fetch courses']);
                return;
            }
    
            // Return the courses as JSON
            header('Content-Type: application/json');
            echo json_encode($courses);
        } catch (Exception $e) {
            // Handle unexpected errors
            error_log("Exception: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'An unexpected error occurred']);
        }
    }

    public function saveSchedule()
{
    try {
        
        $courseId = $_POST['course_id'];
        $programId = $_POST['program_id'];
        $sectionId = $_POST['section_id'];
        $semester = $_POST['sched_semester'];
        $schoolYear = $_POST['sched_sy'];
        $room = $_POST['sched_room'];
        
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        $scheduleModel = new Schedule();

        foreach ($days as $day) {
            $isEnabled = isset($_POST['days']) && in_array($day, $_POST['days']);

            if ($isEnabled) {
                $scheduleData = [
                    'course_id' => $courseId,
                    'program_id' => $programId,
                    'section_id' => $sectionId,
                    'sched_day' => $day,
                    'TIME_FROM' => $_POST[strtolower($day) . '_start'] ?? null,
                    'TIME_TO' => $_POST[strtolower($day) . '_end'] ?? null,
                    'sched_semester' => $semester,
                    'sched_sy' => $schoolYear,
                    'sched_room' => $room
                ];

                $scheduleModel->saveSchedule($scheduleData);
            }
        }

        $this->showSchedule();

    } catch (Exception $e) {
        error_log("Error saving schedule: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'Failed to save schedule.']);
    }
}

    
    

}
