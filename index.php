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
    
    $router->get('/form', '\App\Controllers\FormController@showForm');
    $router->post('/enrollment', '\App\Controllers\FormController@submitEnrollment');

    // Run it!
    $router->run();

} catch (Exception $e) {

    echo json_encode([
        'error' => $e->getMessage()
    ]);

}
