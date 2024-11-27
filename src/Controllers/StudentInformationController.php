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
            'title' => 'Student Information',
            'programs' => $studentInfo->getPrograms(),
        ];

        return $this->render('student-info', $data);
    }

    // public function reviewStudentInfo()
    // {
    //     // Get all data from the form submission
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         // Collect data from the form
    //         $studentInfo = [
    //             'lastname' => $_POST['lastname'] ?? '',
    //             'firstname' => $_POST['firstname'] ?? '',
    //             'middlename' => $_POST['middlename'] ?? '',
    //             'gender' => $_POST['gender'] ?? '',
    //             'date_of_birth' => $_POST['date_of_birth'] ?? '',
    //             'place_of_birth' => $_POST['place_of_birth'] ?? '',
    //             'contact_mobile' => $_POST['contact_mobile'] ?? '',
    //             'email' => $_POST['email'] ?? '',
    //             'emergency_contact_name' => $_POST['emergency_contact_name'] ?? '',
    //             'emergency_contact_mobile' => $_POST['emergency_contact_mobile'] ?? '',
    //             'province' => $_POST['province'] ?? '',
    //             'city' => $_POST['city'] ?? '',
    //             'barangay' => $_POST['barangay'] ?? '',
    //             'street_address' => $_POST['street_address'] ?? '',
    //             'zipcode' => $_POST['zipcode'] ?? '',
    //             'elementary_school' => $_POST['elementary_school'] ?? '',
    //             'high_school' => $_POST['high_school'] ?? '',
    //             'course' => $_POST['course'] ?? '',
    //             // Add any other form fields here
    //         ];

    //         // Pass the data to the view (in your case, reviewStudentInfo mustache)
    //         $this->render('student-info', [
    //             'student-info' => $studentInfo,
    //         ]);
    //     }
    // }

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

            if ($result['row_count'] > 0) {
                $_SESSION['success'] = 'Enrollment submitted successfully!';
            } else {
                $_SESSION['error'] = 'Failed to submit enrollment. Please try again.';
            }

            return $this->showStudentInfo();  // Redirect back to the form view
        }
    }
}