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
    
            // Static credentials for superadmin
            $superadminEmail = "superadmin@example.com";
            $superadminPassword = "superadmin123";
    
            // Check if superadmin credentials match
            if ($data['email'] === $superadminEmail && $data['password'] === $superadminPassword) {
                $_SESSION['user_id'] = 0; // Special ID for superadmin
                $_SESSION['complete_name'] = "Superadmin";
                $_SESSION['role'] = 'superadmin';
    
                return $this->render('root', [
                    'complete_name' => $_SESSION['complete_name'],
                    'isDashboard' => true,
                ]);
            }
    
            $user = new User();
            if ($user->verifyAccess($data['email'], $data['password'])) {
                $sql = "SELECT id, complete_name, email FROM users WHERE email = :email";
                $statement = $user->getDbConnection()->prepare($sql);
                $statement->execute(['email' => $data['email']]);
                $userData = $statement->fetch(PDO::FETCH_ASSOC);
    
                if ($userData) {
                    $_SESSION['user_id'] = $userData['id'];
                    $_SESSION['complete_name'] = $userData['complete_name'];
                    $_SESSION['email'] = $userData['email'];
                    $_SESSION['role'] = 'student';
    
                    return $this->render('studentroot', [
                        'complete_name' => $_SESSION['complete_name'],
                        'isProfile' => true,
                    ]);
                }
            }
    
            $faculty = new Faculty();
            if ($faculty->verifyAccess($data['email'], $data['password'])) {
                $sql = "SELECT id, lastname, firstname, email FROM faculty WHERE email = :email";
                $statement = $faculty->getDbConnection()->prepare($sql);
                $statement->execute(['email' => $data['email']]);
                $facultyData = $statement->fetch(PDO::FETCH_ASSOC);
    
                if ($facultyData) {
                    $_SESSION['user_id'] = $facultyData['id'];
                    $_SESSION['complete_name'] = $facultyData['firstname'] . ' ' . $facultyData['lastname'];
                    $_SESSION['email'] = $facultyData['email'];
                    $_SESSION['role'] = 'faculty';
    
                    return $this->render('root', [
                        'complete_name' => $_SESSION['complete_name'],
                        'isDashboard' => true,
                    ]);
                }
            }
    
            // If no match, set error and re-render login
            $_SESSION['error'] = "Invalid email or password.";
            return $this->render('u-login');
        }
    
        return $this->render('u-login');
    }
    public function logout() {
        $this->initializeSession();

        $_SESSION = [];
        session_destroy();

        // Prevent back button navigation
        header("Cache-Control: no-cache, must-revalidate, max-age=0");
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // In the past
        header("Pragma: no-cache");

        header("Location: /login");
        exit;
    }

    
}
