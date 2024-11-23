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
            'isDashboard' => false,
            'isStudent' => false,
            'isFaculty' => false,
            'isSection' => false,
            'isProfile' => false,
            'isCourse' => true,
            'isProgram' => false,
            'programs' => $programs,
        ];

        return $this->render('root', $data);
    }

    public function listCoursesByProgramAndYear($programId, $year)
    {
        $courseModel = new Course();
        $courses = $courseModel->getCoursesByProgramAndYear($programId, $year);

        echo json_encode($courses);
    }

    public function registerCourse()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;

            $courseModel = new Course();
            $result = $courseModel->save($data);

            if ($result['row_count'] > 0) {
                echo json_encode(['success' => true, 'message' => 'Course added successfully!']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to add course.']);
            }
        }
    }

    public function getCourse($id)
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
            $result = $courseModel->update($id, $data);

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
        $courseModel->delete($id);
    }
}
