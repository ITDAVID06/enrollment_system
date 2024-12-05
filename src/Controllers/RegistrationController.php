<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Registration;

class RegistrationController extends BaseController
{
    // Default method for showing the dashboard
    public function showRegistration(){

        $registration = new Registration();
        $data = [
            'programs' => $registration->getPrograms(),
        ];
        return $this->render('registration', $data);
    
    }

     // Handle enrollment submission
     public function submitEnrollment()
     {
         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
             // Collect form data
             $data = $_POST;
 
             // Handle file upload
             if (isset($_FILES['uploaded_grade_file']) && $_FILES['uploaded_grade_file']['error'] === UPLOAD_ERR_OK) {
                 $uploadDir = __DIR__ . '/../uploads/grades/';
                 $fileName = uniqid() . '_' . basename($_FILES['uploaded_grade_file']['name']);
                 $filePath = $uploadDir . $fileName;
 
                 // Create upload directory if it doesn't exist
                 if (!file_exists($uploadDir)) {
                     mkdir($uploadDir, 0777, true);
                 }
 
                 // Move the uploaded file
                 if (move_uploaded_file($_FILES['uploaded_grade_file']['tmp_name'], $filePath)) {
                     $data['uploaded_grade_file'] = '/uploads/grades/' . $fileName;
                 } else {
                     $_SESSION['error'] = 'Failed to upload grade file.';
                     header('Location: /registration');
                     exit;
                 }
             } else {
                 $_SESSION['error'] = 'Please upload a valid grade file.';
                 header('Location: /registration');
                 exit;
             }
 
             // Save enrollment data
             $registration = new Registration();
             $result = $registration->save($data);
 
             // Check if the insertion was successful
             if ($result['row_count'] > 0) {
                 $_SESSION['success'] = 'Registration submitted successfully!';
                 echo "<script>
                    alert('Registration submitted successfully!');
                    window.location.href = '/';
                </script>";
                 exit;
             } else {
                 $_SESSION['error'] = 'Failed to submit enrollment. Please try again.';
                 header('Location: /registration');
                 exit;
             }
         }
     }
 
}
