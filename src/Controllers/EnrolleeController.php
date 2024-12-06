<?php

namespace App\Controllers;

use App\Models\Enrollee;
use Fpdf\Fpdf;

class EnrolleeController extends BaseController
{

    public function showEnrollees(){
        $this->checkAuthentication();
        $data = [
            'isEnrollees' => true,
        ];
        return $this->render('root', $data);
    }

    public function enrolleeList()
    {
        $model = new Enrollee();
        echo json_encode($model->getAllEnrollees());
    }

    public function getEnrolleeById($id)
    {
        $model = new Enrollee();
        echo json_encode($model->getByEnrolleeId($id));
    }

    public function enrollEnrollee($id)
    {
        $model = new Enrollee();
    
        // Check if the enrollee is already enrolled
        $enrollee = $model->getByEnrolleeId($id);
        if ($enrollee['student_id'] !== null) {
            echo json_encode(['success' => false, 'message' => 'Enrollee is already enrolled.']);
            return;
        }
    
        // Validate incoming data
        $sectionId = $_POST['section_id'] ?? null;
        $studentId = $_POST['student_id'] ?? null;
    
        if (!$sectionId || !$studentId) {
            echo json_encode(['success' => false, 'message' => 'Invalid section or student ID.']);
            return;
        }
    
        // Add student_id and sectionId function
        $result = $model->enlistEnrollee($id, $studentId, $sectionId);
    
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Enrollee successfully enrolled.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to enroll the enrollee.']);
        }
    }

    public function updateEnrollee($id)
    {
        $data = $_POST;
        $model = new Enrollee();
        $result = $model->updateEnrollee($id, $data);

        echo json_encode(['success' => $result]);
    }

    public function deleteEnrollee($id)
    {
        $model = new Enrollee();
        $model->deleteEnrollee($id);
        echo json_encode(['success' => true]);
    }

    public function printAllStudents()
{
    try {
        // Step 1: Fetch students from the database
        $model = new Enrollee();
        $students = $model->getAll();

        if (empty($students)) {
            throw new Exception('No students found in the database.');
        }

        // Step 2: Log fetched data
        error_log('Fetched Students: ' . print_r($students, true));

        // Step 3: Create a new PDF instance
        $this->pdf = new FPDF(); // Initialize FPDF instance
        $this->pdf->AddPage();

        // Set the title
        $this->pdf->SetFont('Arial', 'B', 14);
        $this->pdf->Cell(190, 10, 'Student List', 0, 1, 'C');
        $this->pdf->Ln(5);

        // Set the table header
        $this->pdf->SetFont('Arial', 'B', 10);
        $this->pdf->Cell(20, 10, 'ID', 1);
        $this->pdf->Cell(40, 10, 'Name', 1);
        $this->pdf->Cell(20, 10, 'Gender', 1);
        $this->pdf->Cell(30, 10, 'Section', 1);
        $this->pdf->Cell(30, 10, 'Program', 1);
        $this->pdf->Cell(20, 10, 'Year', 1);
        $this->pdf->Cell(30, 10, 'Mobile', 1);
        $this->pdf->Ln();

        // Populate the table rows
        $this->pdf->SetFont('Arial', '', 10);
        foreach ($students as $student) {
            $this->pdf->Cell(20, 10, $student['id'], 1);
            $this->pdf->Cell(40, 10, $student['last_name'], 1);
            $this->pdf->Cell(20, 10, $student['gender'], 1);
            $this->pdf->Cell(30, 10, $student['section_id'] ?? 'N/A', 1);
            $this->pdf->Cell(30, 10, $student['program_applying_for'] ?? 'N/A', 1);
            $this->pdf->Cell(20, 10, $student['year_level'], 1);
            $this->pdf->Cell(30, 10, $student['contact_mobile'], 1);
            $this->pdf->Ln();
        }

        // Output the PDF
        $this->pdf->Output('D', 'Student_List.pdf'); // Force download
        exit; // Stop further execution after generating PDF

    } catch (Exception $e) {
        // Log and return the error message
        error_log('Error in printAllStudents: ' . $e->getMessage());
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}


}
