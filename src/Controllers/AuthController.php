<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Faculty;
use App\Models\Student;
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

    public function login() {
        $this->initializeSession();
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
    

            // Verify faculty credentials
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

                    $enrolleeModel = new Student();

                    // Fetch recent enrollees
                    $recentEnrollees = $enrolleeModel->getRecentPendingEnrollees();
                
                    // Fetch total students per program
                    $programTotals = $enrolleeModel->getTotalStudentsByProgram();
    
                    return $this->render('root', [
                        'complete_name' => $_SESSION['complete_name'],
                        'isDashboard' => true,
                        'recentEnrollees' => $recentEnrollees ?? [],
                        'programTotals' => $programTotals ?? []
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

        // Clear session data
        session_unset();
        session_destroy();

        // Redirect to login page
        header("Location: /login");
        exit;
    }
}
