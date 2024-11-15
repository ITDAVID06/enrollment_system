<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\User;
use App\Models\Faculty;
use \PDO;
require 'vendor/autoload.php';

class AuthController extends BaseController
{
    public function loginForm()
    {
        $this->initializeSession();

        return $this->render('u-login');
    }

    public function registrationForm()
    {
        $this->initializeSession();

        return $this->render('u-registration');
    }

    public function register()
    {
        $this->initializeSession();
        $data = $_POST;
        $user = new User();
        $result = $user->save($data);

        if ($result['row_count'] > 0) {
           
            $_SESSION['user_id'] = $result['last_insert_id']; 
            $_SESSION['complete_name'] = $data['complete_name'];
            $_SESSION['email'] = $data['email'];
            $_SESSION['password'] = $data['password'];
    
           
            return $this->render('u-login', $data);

        }
    }

    public function login() {
        $this->initializeSession();
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
    
            $user = new User();
            
            // Check if the user exists in the 'users' table (students)
            if ($user->verifyAccess($data['email'], $data['password'])) {
                $sql = "SELECT id, complete_name, email FROM users WHERE email = :email";
                $statement = $user->getDbConnection()->prepare($sql); 
                $statement->execute(['email' => $data['email']]);
                $userData = $statement->fetch(PDO::FETCH_ASSOC);
    
                // Ensure userData is valid before using it
                if ($userData !== false) {
                    // Set session variables for the student
                    $_SESSION['user_id'] = $userData['id'];
                    $_SESSION['complete_name'] = $userData['complete_name'];
                    $_SESSION['email'] = $userData['email'];
    
                    // Redirect to student root
                    $templateData = [
                        'complete_name' => $userData['complete_name'],
                        'email' => $userData['email'],
                        'isProfile' => true,
                    ];
                    return $this->render('studentroot', $templateData);
                }
            }
    
            $faculty = new Faculty();  
            if ($faculty->verifyAccess($data['email'], $data['password'])) {
                $sql = "SELECT id, lastname, firstname, email FROM faculty WHERE email = :email";
                $statement = $faculty->getDbConnection()->prepare($sql); 
                $statement->execute(['email' => $data['email']]);
                $facultyData = $statement->fetch(PDO::FETCH_ASSOC);
    
                if ($facultyData !== false) {
                    $_SESSION['user_id'] = $facultyData['id'];
                    $_SESSION['complete_name'] = $facultyData['firstname'] . ' ' . $facultyData['lastname'];
                    $_SESSION['email'] = $facultyData['email'];

                    $templateData = [
                        // 'complete_name' => $facultyData['complete_name'],
                        // 'email' => $facultyData['email'],
                        'isDashboard' => true,
                    ];
    
                    // Redirect to faculty root
                    return $this->render('root', $templateData); 
                }
            }
    
            // If neither table has a match, set an error message
            $_SESSION['error'] = "Invalid email or password.";
            
            return $this->render('u-login'); 
        }
    
        return $this->render('u-login'); 
    }
    
    public function logout()
{
    $this->initializeSession();

    $_SESSION = [];

    session_destroy();

    return $this->render('u-login'); 
    exit;
}

    
}
