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
    $router->get('/home', '\App\Controllers\HomeController@index');
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
    $router->get('/courses/search', '\App\Controllers\CourseController@searchCourses');

    $router->get('/sections', '\App\Controllers\SectionController@showSections');




    $router->get('/sections/all', '\App\Controllers\SectionController@list');
    $router->get('/sections/list', '\App\Controllers\SectionController@getAllSections');

    $router->post('/section', '\App\Controllers\SectionController@registerSection');
    $router->get('/section/{id}', '\App\Controllers\SectionController@getSection');
    $router->get('/section/course/{id}', '\App\Controllers\SectionController@getCourse');
    $router->post('/section/update/{id}', '\App\Controllers\SectionController@updateSection');
    $router->delete('/section/{id}', '\App\Controllers\SectionController@deleteSection');
    $router->get('/sections/courses/{sectionId}', '\App\Controllers\SectionController@getCoursesBySection');


    $router->get('/schedule', '\App\Controllers\ScheduleController@showSchedule');
    $router->get('/schedule/{programId}/{sectionId}', '\App\Controllers\ScheduleController@getCoursesByProgramAndSection');
    $router->get('/program-sections', '\App\Controllers\ScheduleController@getProgramSections');
    

    $router->get('/get-courses/{sectionId}', '\App\Controllers\SectionController@getCourses');
    $router->post('/schedule/update/{courseId}', '\App\Controllers\SectionController@updateSchedule');
    $router->get('/schedule/{schedID}', '\App\Controllers\SectionController@getAllSchedules');
    $router->get('/get-schedule/{course_id}/{sectionId}', '\App\Controllers\SectionController@getAllSchedules');
    $router->get('/schedule/{courseId}', '\App\Controllers\SectionController@getSchedule');
    $router->post('/schedule/save/{course_id}', '\App\Controllers\SectionController@saveSchedule');
    $router->post('/save-schedule', '\App\Controllers\SectionController@saveSchedule');


    $router->get('/faculty', '\App\Controllers\FacultyController@showFaculty'); // Show faculty page
    $router->post('/faculty', '\App\Controllers\FacultyController@register'); // Add new faculty
    $router->get('/faculty/list', '\App\Controllers\FacultyController@list'); // Fetch all faculty for the table
    $router->get('/faculty/{id}', '\App\Controllers\FacultyController@get'); // Fetch a single faculty member
    $router->post('/faculty/update/{id}', '\App\Controllers\FacultyController@update'); // Update a faculty member
    $router->delete('/faculty/{id}', '\App\Controllers\FacultyController@delete'); // Delete a faculty member


    $router->get('/profile', '\App\Controllers\ProfileController@showProfile');
    // Routes for Faculty
    $router->post('/faculty/update-profile', '\App\Controllers\FacultyController@updateProfile');

    // Routes for Students
    $router->post('/student/update-profile', '\App\Controllers\StudentController@updateProfile');

    $router->get('/studentaccount', '\App\Controllers\StudentController@showStudentAccount');


    $router->delete('/schedule/{course_id}/{section_id}', '\App\Controllers\ScheduleController@deleteSchedules');


    $router->get('/schedule/{programId}/{sectionId}', '\App\Controllers\ScheduleController@getCoursesByProgramAndSection');


    $router->get('/registration', '\App\Controllers\RegistrationController@showRegistration');
    $router->post('/submit-enrollment', '\App\Controllers\RegistrationController@submitEnrollment');


    // $router->post('/check-email', '\App\Controllers\RegistrationController@checkEmail');
    $router->get('/success', '\App\Controllers\RegistrationController@success');
    //link for enrollees admin
    $router->get('/enrollee', '\App\Controllers\EnrolleeController@showEnrollees');


    $router->get('/enrollee/list', '\App\Controllers\EnrolleeController@list');
    $router->get('/enrollee/{id}', '\App\Controllers\EnrolleeController@get');

    $router->post('/enrollee/enroll/{id}', '\App\Controllers\EnrolleeController@enroll');
    $router->post('/enrollee/update/{id}', '\App\Controllers\EnrolleeController@update');

    $router->get('/student', '\App\Controllers\StudentController@showStudent');
    $router->get('/student/list', '\App\Controllers\StudentController@list');
    $router->get('/student/{id}', '\App\Controllers\StudentController@get');
    $router->post('/student/update/{id}', '\App\Controllers\StudentController@update');

    $router->get('/student/schedule/{sectionId}', '\App\Controllers\StudentController@getCourses');
    $router->get('/student/courses/{sectionId}', '\App\Controllers\StudentController@getSchedulesBySection');

    
    // routes.php
    $router->get('/enrollee/print-all', '\App\Controllers\EnrolleeController@printAllStudents');
    $router->get('/generate-sample-pdf', '\App\Controllers\EnrolleeController@generateSamplePDF');
    $router->get('/generate-pdf', '\App\Controllers\StudentController@generateSamplePDF');

    $router->get('/students/chart-data', '\App\Controllers\StudentController@getChartData');
    $router->post('/student/remove-section/{id}', '\App\Controllers\StudentController@removeSection');

    $router->post('/send-schedule-email', '\App\Controllers\StudentController@sendScheduleEmail');




    $router->get('/programs/list', '\App\Controllers\EnrolleeController@listEnrollees');
    // Run it!
    $router->run();

} catch (Exception $e) {

    echo json_encode([
        'error' => $e->getMessage()
    ]);

}
