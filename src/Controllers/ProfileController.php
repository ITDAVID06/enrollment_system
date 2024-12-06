<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Faculty;

class ProfileController extends BaseController
{
    // Default method for showing the dashboard
    public function showProfile(){
        $this->checkAuthentication();
        $data = [
            'isProfile' => true,
        ];
        return $this->render('root', $data);
    }

    public function updateProfile() 
    {
        $this->initializeSession();
    
        $data = json_decode(file_get_contents("php://input"), true);
    
        $email = $data['email'];
        $password = $data['password'];
        $facultyId = $_SESSION['user_id'];
    
        $facultyModel = new Faculty();
    
        // Check if email is already taken
        if ($facultyModel->isEmailTaken($email, $facultyId)) {
            http_response_code(400);
            echo json_encode(['message' => 'Email is already taken.']);
            return;
        }
    
        // Update email and password (if provided)
        if ($facultyModel->updateEmailAndPassword($facultyId, $email, $password)) {
            http_response_code(200);
            echo json_encode(['message' => 'Profile updated successfully.']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Failed to update profile.']);
        }
    }
    


}
