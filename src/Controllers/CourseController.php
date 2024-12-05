<?php

namespace App\Controllers;

use App\Models\Course;
use App\Models\Program;
use App\Controllers\BaseController;

class CourseController extends BaseController
{
    public function showCourses()
    {
        $programModel = new Program();
        $programs = $programModel->getAllPrograms();

        $data = [
            'isCourse' => true,
            'programs' => $programs,
        ];

        return $this->render('root', $data);
    }

    public function listCoursesByProgramAndYear($programId, $year)
    {
        $courseModel = new Course();
    
        // Check if a semester is provided
        $semester = $_GET['semester'] ?? '1st Sem'; 
    
        // Fetch courses with or without a semester filter
        $courses = $courseModel->getCoursesByProgramAndYear($programId, $year, $semester);
    
        echo json_encode($courses);
    }

    public function searchCourses()
    {
        $courseModel = new Course();

        // Get the search query from the request
        $query = isset($_GET['query']) ? $_GET['query'] : '';

        // Fetch courses matching the search query
        $courses = $courseModel->searchCourses($query);

        echo json_encode($courses);
    }
 
    public function addCourse()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;

            $courseModel = new Course();
            $result = $courseModel->saveCourse($data);

            if ($result['row_count'] > 0) {
                echo json_encode(['success' => true, 'message' => 'Course added successfully!']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to add course.']);
            }
        }
    }

    public function getCourseById($id)
    {
        $courseModel = new Course();
        $course = $courseModel->getCourseById($id);
    
        if ($course) {
            echo json_encode($course);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Course not found']);
        }
    }
    
    public function updateCourse($id)
    {
        try {
            $data = $_POST;

            $courseModel = new Course();
            $result = $courseModel->updateCourse($id, $data);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Course updated successfully!']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to update course.']);
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deleteCourse($id)
    {
        $courseModel = new Course();
        $courseModel->deleteCourse($id);
    }
}
