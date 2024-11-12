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
    $router->get('/dashboard', '\App\Controllers\DashboardController@showDashboard');
    // $router->get('/student', '\App\Controllers\StudentController@showStudent');
    $router->get('/program', '\App\Controllers\ProgramController@showProgram');
    // $router->get('/course', '\App\Controllers\CourseController@showCourse');
    // $router->get('/section', '\App\Controllers\SectionController@showSection');
    // $router->get('/faculty', '\App\Controllers\FacultyController@showFaculty');
    // $router->get('/profile', '\App\Controllers\ProfileController@showProfile');

    // Run it!
    $router->run();

} catch (Exception $e) {

    echo json_encode([
        'error' => $e->getMessage()
    ]);

}
