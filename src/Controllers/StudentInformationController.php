<?php
namespace App\Controllers;

use App\Models\StudentInformation;
use App\Controllers\BaseController;

class StudentInformationController extends BaseController
{
    public function showStudentInfo()
    { 
        $studentInfo  = new StudentInformation();

        // Retrieve dropdown data
        $data = [
            'programs' => $studentInfo->getPrograms(),
            'isProfile' => true,
        ];

        return $this->render('studentroot', $data);
    }

    public function submitStudentInformationForm()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
    
            // Ensure the uploads directory exists
            $uploadsDir = 'uploads/';
            if (!is_dir($uploadsDir)) {
                mkdir($uploadsDir, 0777, true);  // Create the directory if it doesn't exist
            }
    
            // Initialize variables for the file paths
            $profilePicturePath = '';
            $gradeFilePath = '';
    
            // Handle profile picture upload
            if (isset($_FILES['profile_picture_path']) && $_FILES['profile_picture_path']['error'] === UPLOAD_ERR_OK) {
                $profilePictureTmpPath = $_FILES['profile_picture_path']['tmp_name'];
                $profilePictureName = $_FILES['profile_picture_path']['name'];
                $profilePicturePath = $uploadsDir . $profilePictureName;  // Store file in 'uploads/'
    
                // Move the uploaded file to the desired location
                if (!move_uploaded_file($profilePictureTmpPath, $profilePicturePath)) {
                    $_SESSION['error'] = 'Error: Failed to upload profile picture.';
                    return $this->showStudentInfo(); // Redirect back to the form view
                }
            } else {
                $_SESSION['error'] = 'Error: Profile picture file not uploaded.';
                return $this->showStudentInfo(); // Redirect back to the form view
            }
    
            // Handle grade file upload
            if (isset($_FILES['grade_file_path']) && $_FILES['grade_file_path']['error'] === UPLOAD_ERR_OK) {
                $gradeFileTmpPath = $_FILES['grade_file_path']['tmp_name'];
                $gradeFileName = $_FILES['grade_file_path']['name'];
                $gradeFilePath = $uploadsDir . $gradeFileName;  // Store file in 'uploads/'
    
                // Move the uploaded file to the desired location
                if (!move_uploaded_file($gradeFileTmpPath, $gradeFilePath)) {
                    $_SESSION['error'] = 'Error: Failed to upload grade file.';
                    return $this->showStudentInfo(); // Redirect back to the form view
                }
            } else {
                $_SESSION['error'] = 'Error: Grade file not uploaded.';
                return $this->showStudentInfo(); // Redirect back to the form view
            }
    
            // Add file paths to the student data array
            $data['profile_picture_path'] = $profilePicturePath;
            $data['grade_file_path'] = $gradeFilePath;
    
            // Save the student information along with file paths
            $studentInfo = new StudentInformation();
            $result = $studentInfo->saveStudentInformation($data);
    
            // Store the result in the session to persist it after redirect
            if ($result['row_count'] > 0) {
                $_SESSION['success'] = 'Enrollment submitted successfully!';
                $_SESSION['student_data'] = $data;  // Store the submitted data in session
            } else {
                $_SESSION['error'] = 'Failed to submit enrollment. Please try again.';
            }
    
            // Redirect to the student view sheet
            return $this->showStudentViewSheet();  // Redirect back to the form view
        }
    }
    

    //for view tab
    public function showStudentViewSheet()
    {
        $data = [
            'isViewSheet' => true,
        ];

        return $this->render('studentroot', $data);
    }

 

    //for edit tab
    public function showStudentEditSheet()
    { 
        $data = [
            'isEditSheet' => true,
        ];

        return $this->render('studentroot', $data);
    }

    public function viewStudentInfo($id)
    {
        $studentInfo = new StudentInformation ();
        echo json_encode($studentInfo)-> getStudentInformationById($id);
    }
}