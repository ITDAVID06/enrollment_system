<?php

require "vendor/autoload.php";
require "init.php";

// Database connection object (from init.php (DatabaseConnection))
global $conn;

    try {

    // Create Router instance
    $router = new \Bramus\Router\Router();

    // Index Page
    $router->get('/', '\App\Controllers\HomeController@index');
    $router->get('/home', '\App\Controllers\HomeController@index');

    // Login Page
    $router->get('/login', '\App\Controllers\AuthController@loginForm');
    $router->post('/login', '\App\Controllers\AuthController@login');
    $router->get('/logout', '\App\Controllers\AuthController@logout');

    // Registration Page
    $router->get('/registration', '\App\Controllers\RegistrationController@showRegistration');
    $router->post('/submit-enrollment', '\App\Controllers\RegistrationController@submitEnrollment');

    // Dashboard Page
    $router->get('/dashboard', '\App\Controllers\DashboardController@showDashboard');
    $router->get('/students/chart-data', '\App\Controllers\DashboardController@getChartData');

    // Enrollees Page
    $router->get('/enrollee', '\App\Controllers\EnrolleeController@showEnrollees');
    $router->get('/enrollee/list', '\App\Controllers\EnrolleeController@enrolleeList');
    $router->get('/enrollee/{id}', '\App\Controllers\EnrolleeController@getEnrolleeById');
    $router->post('/enrollee/enroll/{id}', '\App\Controllers\EnrolleeController@enrollEnrollee'); 
    $router->post('/enrollee/update/{id}', '\App\Controllers\EnrolleeController@updateEnrollee');
    $router->delete('/enrollee/delete/{id}', '\App\Controllers\EnrolleeController@deleteEnrollee');

    // Student Page
    $router->get('/student', '\App\Controllers\StudentController@showStudent');
    $router->get('/student/list', '\App\Controllers\StudentController@listStudents');
    $router->get('/student/{id}', '\App\Controllers\StudentController@getStudentById');
    $router->post('/student/update/{id}', '\App\Controllers\StudentController@updateStudentSection');
    $router->post('/student/remove-section/{id}', '\App\Controllers\StudentController@removeSectionStudent');
    $router->post('/send-schedule-email', '\App\Controllers\StudentController@sendScheduleEmail');
    $router->get('/generate-pdf', '\App\Controllers\StudentController@generateSamplePDF');

    // Program Page
    $router->get('/program', '\App\Controllers\ProgramController@showProgram');
    $router->get('/program/list', '\App\Controllers\ProgramController@listPrograms');
    $router->post('/program', '\App\Controllers\ProgramController@addProgram');
    $router->get('/program/{id}', '\App\Controllers\ProgramController@getPrograms');
    $router->post('/program/update/{id}', '\App\Controllers\ProgramController@updateProgram');

    // Courses Page
    $router->get('/courses', '\App\Controllers\CourseController@showCourses');
    // used in Student viewing Schedule and Schedule Page
    $router->get('/courses/{programId}/{year}', '\App\Controllers\CourseController@listCoursesByProgramAndYear');
    $router->get('/courses/search', '\App\Controllers\CourseController@searchCourses');
    $router->post('/course', '\App\Controllers\CourseController@addCourse');
    $router->get('/course/{id}', '\App\Controllers\CourseController@getCourseById');
    $router->post('/course/update/{id}', '\App\Controllers\CourseController@updateCourse');
    $router->delete('/course/delete/{id}', '\App\Controllers\CourseController@deleteCourse');

    // Section Page
    $router->get('/sections', '\App\Controllers\SectionController@showSections');
    $router->get('/sections/all', '\App\Controllers\SectionController@listSections'); // used by enrollee and student to populate sections .js
    $router->get('/sections/list', '\App\Controllers\SectionController@getAllSections'); // load program sections in section.js
    $router->post('/section', '\App\Controllers\SectionController@addSection');
    $router->get('/section/{id}', '\App\Controllers\SectionController@getSection');
    $router->post('/section/update/{id}', '\App\Controllers\SectionController@updateSection');
    $router->delete('/section/{id}', '\App\Controllers\SectionController@deleteSection');

    // Schedule Page
    $router->get('/schedule', '\App\Controllers\ScheduleController@showSchedule');
    // used in Student View also
    $router->get('/sections/courses/{sectionId}', '\App\Controllers\ScheduleController@getScheduleBySection');
    $router->delete('/schedule/{course_id}/{section_id}', '\App\Controllers\ScheduleController@deleteSchedules');
    $router->get('/schedule/program-sections', '\App\Controllers\ScheduleController@getProgramSections'); // load the sections on the left
    $router->post('/save-schedule', '\App\Controllers\ScheduleController@saveSchedule'); // saves the schedule in the schedule table

    // Faculty Page
    $router->get('/faculty', '\App\Controllers\FacultyController@showFaculty'); // Show faculty page
    $router->post('/faculty', '\App\Controllers\FacultyController@addFaculty'); // Add new faculty
    $router->get('/faculty/list', '\App\Controllers\FacultyController@listFaculty'); // Fetch all faculty for the table
    $router->get('/faculty/{id}', '\App\Controllers\FacultyController@getFaculty'); // Fetch a single faculty member
    $router->post('/faculty/update/{id}', '\App\Controllers\FacultyController@updateFaculty'); // Update a faculty member
    $router->delete('/faculty/{id}', '\App\Controllers\FacultyController@deleteFaculty'); // Delete a faculty member

    // Account Page
    $router->get('/profile', '\App\Controllers\ProfileController@showProfile');
    $router->post('/faculty/update-profile', '\App\Controllers\ProfileController@updateProfile');

    // Run it!
    $router->run();

    } catch (Exception $e) {

        echo json_encode([
            'error' => $e->getMessage()
        ]);

}
