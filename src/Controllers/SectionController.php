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
            'programs' => $programs, // Pass program list to the view
        ];

        return $this->render('root', $data);
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


    // Add a new section
    public function registerSection()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;

            $sectionModel = new Section();
            $result = $sectionModel->save($data);

            if ($result['row_count'] > 0) {
                echo json_encode(['success' => true, 'message' => 'Section added successfully!']);
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


   public function getCoursesBySection($sectionId)
{
    $sql = " SELECT courses.id AS course_id, courses.course_code AS course_code, schedule.TIME_FROM, schedule.TIME_TO, schedule.sched_day, schedule.sched_semester, schedule.sched_sy, schedule.sched_room, schedule.section_id, schedule.PROGRAM_ID FROM courses LEFT JOIN schedule ON courses.id = schedule.COURSE_ID WHERE schedule.section_id = :sectionId;";

    $statement = $this->db->prepare($sql);
    $courses = $statement->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($courses);
}

public function getCourses()
{
    try {
        $courseModel = new Course(); // Assuming the model is named `Course`

        // Call the model method
        $courses = $courseModel->getCourses();

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








    // Update schedule for a course
    public function updateSchedule($courseId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;

            $scheduleModel = new Schedule();
            $scheduleModel->updateSchedule($courseId, $data);

            echo json_encode(['success' => true, 'message' => 'Schedule updated successfully!']);
        }
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

}
