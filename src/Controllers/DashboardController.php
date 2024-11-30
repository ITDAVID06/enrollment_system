<?php

namespace App\Controllers;

Use App\Models\Student;
use App\Controllers\BaseController;


class DashboardController extends BaseController
{
    // Default method for showing the dashboard
    public function showDashboard(){

        $this->initializeSession();

        $enrolleeModel = new Student();

        // Fetch recent enrollees
        $recentEnrollees = $enrolleeModel->getRecentPendingEnrollees();
    
        // Fetch total students per program
        $programTotals = $enrolleeModel->getTotalStudentsByProgram();
    

        error_log("Session complete_name: " . ($_SESSION['complete_name'] ?? 'Not set'));
        error_log("Session email: " . ($_SESSION['email'] ?? 'Not set'));

        $data = [
            'isDashboard' => true, 
            'isStudent' => false,
            'isFaculty' => false,
            'isSection' => false,
            'isProfile' => false,
            'isCourse' => false,
            'isProgram' => false,
            'complete_name' => $_SESSION['complete_name'] ?? '',
            'email' => $_SESSION['email'] ?? '',
            'recentEnrollees' => $recentEnrollees ?? [],
            'programTotals' => $programTotals ?? [] // Ensure it defaults to an empty array
        ];
        return $this->render('root', $data);
    }


    public function showSection($section = 'dashboard'){
        $data = [
            'isDashboard' => false,
            'isStudent' => false,
            'isFaculty' => false,
            'isSection' => false,
            'isProfile' => false,
            'isCourse' => false,
            'isProgram' => false,
            'complete_name' => $_SESSION['complete_name'] ?? '',
            'email' => $_SESSION['email'] ?? '',
        ];

        switch ($section) {
            case 'dashboard':
                $data['isDashboard'] = true;
                break;
            case 'student':
                $data['isStudent'] = true;
                break;
            case 'faculty':
                $data['isFaculty'] = true;
                break;
            case 'section':
                $data['isSection'] = true;
                break;
            case 'profile':
                $data['isProfile'] = true;
                break;
            case 'course':
                $data['isCourse'] = true;
                break;
            case 'program':
                $data['isProgram'] = true;
                break;
            default:
                $data['isDashboard'] = true; 
                break;
        }

        return $this->render('root', $data);
    }
}
