<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\User;
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
        // Save the registration to database
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

    public function login(){
        $this->initializeSession();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = $_POST;

        $user = new User();
        
        if ($user->verifyAccess($data['email'], $data['password'])) {
            
            $sql = "SELECT id, complete_name, email FROM users WHERE email = :email";
            $statement = $user->getDbConnection()->prepare($sql); 
            $statement->execute(['email' => $data['email']]);
            $userData = $statement->fetch(PDO::FETCH_ASSOC);
            
            
            $_SESSION['user_id'] = $userData['id'];
            $_SESSION['complete_name'] = $userData['complete_name'];
            $_SESSION['email'] = $userData['email'];

            
            $templateData = [
                'complete_name' => $userData['complete_name'],
                'email' => $userData['email'],
                'isDashboard' => true,
            ];

            
            return $this->render('root', $templateData);
        } else {
            $_SESSION['error'] = "Invalid email or password.";
            return $this->render('u-login'); 
        }
    }
    
    return $this->render('u-login'); 
    }

    
}
