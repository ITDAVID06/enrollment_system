<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    // Default method for showing the dashboard
    public function showDashboard(){
        $data = [
            'isDashboard' => true, 
            'isStudent' => false,
            'isFaculty' => false,
            'isSection' => false,
            'isProfile' => false,
            'isCourse' => false,
            'isProgram' => false,
            'user_name' => $_SESSION['complete_name'] ?? 'User', // Add user name from session
            'active_section' => 'Dashboard' // Set the active section
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
            'user_name' => $_SESSION['complete_name'] ?? 'User', // Add user name from session
            'active_section' => 'Dashboard' // Set the active section
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
