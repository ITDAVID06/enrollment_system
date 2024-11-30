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

    public function showSchedule()
    {
        $data = [
            'isSchedule' => true,
        ];

        return $this->render('root', $data);
    }

    public function list()
{
    $model = new Section(); // Assuming a Section model exists
    echo json_encode($model->getAll());
}


    public function getCourse($id)
{
    $courseModel = new Course();
    $course = $courseModel->getCourseById($id);

    header('Content-Type: application/json');
    echo json_encode($course);
}

    
    public function getAllSections()
    {
        $sectionModel = new Section();
        $sections = $sectionModel->getAllSections();

        header('Content-Type: application/json');
        echo json_encode(['sections' => $sections]);
    }



    // Register a new section and create schedule entries for associated courses
    public function registerSection()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
    
            $sectionModel = new Section();
            $courseModel = new Course(); // Ensure Course model is available
            $scheduleModel = new Schedule(); // Ensure Schedule model is available
    
            $result = $sectionModel->save($data);
    
            if ($result['row_count'] > 0) {
                // Use the Section model's database connection to get the last inserted ID
                $sectionId = $result['last_insert_id'];
                
                echo "Section ID: $sectionId";
                error_log($sectionId);
                // Fetch courses associated with the program and year level
                $courses = $courseModel->getCoursesByProgramAndYear($data['program_id'], $data['year_level'], $data['semester']);
    
                // Create schedules for each course in the section
                foreach ($courses as $course) {
                    $scheduleModel->save([
                        'program_id' => $data['program_id'],
                        'section_id' => $sectionId,
                        'course_id' => $course['id'], // Assuming 'id' is the primary key in the courses table
                        'sched_semester' => $data['semester'],
                    ]);
                }
    
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
            $result = $sectionModel->update($id, $data);

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
        $sectionModel->delete($id);
    }


    public function getCoursesBySection($section_id)
    {
        try {

            // $semester = isset($_GET['semester']) ? $_GET['semester'] : '1st Sem';
            // Load the model
            $courseModel = new Course(); // Assuming the model is named `Course`
            
            // Call the model method to get courses for the section
            $courses = $courseModel->getCourses($section_id);
            
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

    

    public function getCourses($section_id)
    {
        try {
            $sectionModel = new Section();
            $courseModel = new Course();
    
            // Fetch section details
            $section = $sectionModel->getSectionById($section_id);
    
            if (!$section) {
                http_response_code(404);
                echo json_encode(['error' => 'Section not found']);
                return;
            }
    
            // Fetch courses and schedules for the section
            $courses = $courseModel->getCoursesWithSchedules(
                $section['program_id'],
                $section['year_level'],
                $section['semester'],
                $section['name'] // Use section name for matching in the schedule table
            );
    
            if (!$courses) {
                http_response_code(404);
                echo json_encode(['message' => 'No courses found for this section.']);
                return;
            }
    
            // Return the courses and schedules as JSON
            header('Content-Type: application/json');
            echo json_encode($courses);
        } catch (Exception $e) {
            error_log("Exception: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'An unexpected error occurred']);
        }
    }
    

    
    
    // Update schedule for a course
    public function updateSchedule($course_id)
    {
        $scheduleModel = new Schedule();
        $days = ["monday", "tuesday", "wednesday", "thursday", "friday"];
        $updatedResults = [];
        $success = true;
    
        // Validate that required POST data exists
        if (empty($_POST['sched_semester']) || empty($_POST['sched_sy']) || empty($_POST['sched_room'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Missing required fields: sched_semester, sched_sy, or sched_room',
            ]);
            return;
        }
    
        // Loop through each day
        foreach ($days as $day) {
            // Check if start and end times exist for this day
            if (!empty($_POST[$day . '_start']) && !empty($_POST[$day . '_end'])) {
                // Prepare data for the current day
                $data = [
                    'course_id' => $course_id,
                    'TIME_FROM' => $_POST[$day . '_start'],
                    'TIME_TO' => $_POST[$day . '_end'],
                    'sched_day' => ucfirst($day), // Capitalize the day (e.g., "Monday")
                    'sched_semester' => $_POST['sched_semester'],
                    'sched_sy' => $_POST['sched_sy'],
                    'sched_room' => $_POST['sched_room'],
                    'section_id' => $_POST['section_id'] ?? null,
                    'program_id' => $_POST['program_id'] ?? null,
                ];
    
                // Log the prepared data for debugging
                error_log("Updating schedule for $day: " . print_r($data, true));
    
                // Attempt to update the schedule
                $updated = $scheduleModel->updateScheduleModal($course_id, $data);
    
                // Track the result
                $updatedResults[$day] = $updated;
    
                if (!$updated) {
                    $success = false; // Mark as failure if any update fails
                    error_log("Failed to update schedule for $day with data: " . print_r($data, true));
                }
            } else {
                error_log("Skipping update for $day: Missing start or end time");
            }
        }
    
        // Return the result of the updates
        echo json_encode([
            'success' => $success,
            'results' => $updatedResults,
        ]);
    }
    
    
    public function addSchedule()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = $_POST;

        $sql = "
            INSERT INTO schedule (
                TIME_FROM, TIME_TO, sched_time, sched_day, sched_semester, sched_sy, sched_room, SECTION, PROGRAM_ID, COURSE_ID
            ) 
            VALUES (
                :TIME_FROM, :TIME_TO, :sched_time, :sched_day, :sched_semester, :sched_sy, :sched_room, :SECTION, :PROGRAM_ID, :COURSE_ID
            )
        ";

        $statement = $this->db->prepare($sql);
        $result = $statement->execute([
            'TIME_FROM' => $data['TIME_FROM'],
            'TIME_TO' => $data['TIME_TO'],
            'sched_time' => $data['sched_time'],
            'sched_day' => $data['sched_day'],
            'sched_semester' => $data['sched_semester'],
            'sched_sy' => $data['sched_sy'],
            'sched_room' => $data['sched_room'],
            'SECTION' => $data['SECTION'],
            'PROGRAM_ID' => $data['PROGRAM_ID'],
            'COURSE_ID' => $data['COURSE_ID']
        ]);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Schedule added successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to add schedule']);
        }
    }
}

private $scheduleModel;

public function __construct()
{
    $this->scheduleModel = new Schedule();
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



public function getSchedule($courseId)
{
    try {
        $scheduleModel = new Schedule();
        $schedules = $scheduleModel->getScheduleByCourseId($courseId);

        if (!$schedules || count($schedules) === 0) {
            // No schedules found, return an empty array
            $schedules = [];
        }

        header('Content-Type: application/json');
        echo json_encode($schedules);
    } catch (Exception $e) {
        error_log("Exception: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'An unexpected error occurred']);
    }
}

public function getupdateSchedule($schedID) {
    $scheduleModel = new Schedule();
    echo json_encode($scheduleModel->getScheduleById($schedID));
}

public function getAllSchedules($course_id, $sectionId){

        $scheduleModel = new Schedule();
        $schedules = $scheduleModel->getAllSchedules($course_id, $sectionId);

        header('Content-Type: application/json');
        echo json_encode($schedules);
}




}
