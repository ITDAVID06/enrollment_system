<?php

require "vendor/autoload.php";
require "init.php";

// Database connection object (from init.php (DatabaseConnection))
global $conn;

try {

    // Create Router instance
    $router = new \Bramus\Router\Router();

    // Define routes
    $router->get('/', '\App\Controllers\HomeController@index');
    $router->get('/login', '\App\Controllers\AuthController@loginForm');
    $router->post('/login', '\App\Controllers\AuthController@login');
    $router->get('/login', '\App\Controllers\AuthController@logout');

    $router->get('/register', '\App\Controllers\AuthController@registrationForm');
    $router->post('/register', '\App\Controllers\AuthController@register');
    
    $router->get('/dashboard', '\App\Controllers\DashboardController@showDashboard');
    // $router->get('/student', '\App\Controllers\StudentController@showStudent');


    $router->get('/program', '\App\Controllers\ProgramController@showProgram');

    $router->get('/programs', '\App\Controllers\ProgramController@list');
    $router->post('/program', '\App\Controllers\ProgramController@register');
    $router->get('/program/list', '\App\Controllers\ProgramController@list');
    $router->get('/program/{id}', '\App\Controllers\ProgramController@get');
    $router->post('/program/update/{id}', '\App\Controllers\ProgramController@update');
    $router->delete('/program/{id}', '\App\Controllers\ProgramController@delete');

    $router->get('/courses', '\App\Controllers\CourseController@showCourses');
    $router->get('/courses/{programId}/{year}', '\App\Controllers\CourseController@listCoursesByProgramAndYear');
    $router->post('/course', '\App\Controllers\CourseController@registerCourse');
    $router->get('/course/{id}', '\App\Controllers\CourseController@getCourse');
    $router->post('/course/update/{id}', '\App\Controllers\CourseController@updateCourse');
    $router->delete('/course/delete/{id}', '\App\Controllers\CourseController@deleteCourse');
    

    $router->get('/sections', '\App\Controllers\SectionController@showSections');
    $router->get('/api/sections', '\App\Controllers\SectionController@getAllSections');





    $router->post('/section', '\App\Controllers\SectionController@registerSection');
    $router->get('/section/{id}', '\App\Controllers\SectionController@getSection');
    $router->get('/section/course/{id}', '\App\Controllers\SectionController@getCourse');
    $router->post('/section/update/{id}', '\App\Controllers\SectionController@updateSection');
    $router->delete('/section/{id}', '\App\Controllers\SectionController@deleteSection');
    $router->get('/sections/courses/{sectionId}', '\App\Controllers\SectionController@getCoursesBySection');


    
    $router->post('/schedule/update/{courseId}', '\App\Controllers\SectionController@updateSchedule');

    $router->get('/get-courses/{sectionId}', '\App\Controllers\SectionController@getCourses');
    // In your routing configuration



    // $router->get('/schedule/{schedID}', '\App\Controllers\SectionController@getupdateSchedule');
    $router->get('/schedule/{schedID}', '\App\Controllers\SectionController@getAllSchedules');

    $router->get('/get-schedule/{course_id}/{sectionId}', '\App\Controllers\SectionController@getAllSchedules');

    $router->get('/schedule/{courseId}', '\App\Controllers\SectionController@getSchedule');
    $router->post('/schedule/save/{course_id}', '\App\Controllers\SectionController@saveSchedule');
    $router->post('/save-schedule', '\App\Controllers\SectionController@saveSchedule');





    // $router->get('/course', '\App\Controllers\CourseController@showCourse');
    // $router->get('/section', '\App\Controllers\SectionController@showSection');

    $router->get('/faculty', '\App\Controllers\FacultyController@showFaculty'); // Show faculty page
    $router->post('/faculty', '\App\Controllers\FacultyController@register'); // Add new faculty
    $router->get('/faculty/list', '\App\Controllers\FacultyController@list'); // Fetch all faculty for the table
    $router->get('/faculty/{id}', '\App\Controllers\FacultyController@get'); // Fetch a single faculty member
    $router->post('/faculty/update/{id}', '\App\Controllers\FacultyController@update'); // Update a faculty member
    $router->delete('/faculty/{id}', '\App\Controllers\FacultyController@delete'); // Delete a faculty member


    // $router->get('/profile', '\App\Controllers\ProfileController@showProfile');

    $router->get('/studentprofile', '\App\Controllers\StudentController@showStudentProfile');

    // Run it!
    $router->run();

} catch (Exception $e) {

    echo json_encode([
        'error' => $e->getMessage()
    ]);

}
