<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Student;
use Fpdf\Fpdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



class StudentController extends BaseController
{

public function showStudent(){
    $this->checkAuthentication();
        $data = [
            'isStudent' => true,
        ];
        return $this->render('root', $data);
    }

  // Fetch all students with section_id and student_id
  public function listStudents()
  {
      $model = new Student();
      $students = $model->getEnrolledStudents();

      header('Content-Type: application/json');
      echo json_encode($students);
  }

  // Fetch details of a single student by ID
  public function getStudentById($id)
  {
      $model = new Student();
      $student = $model->getStudentById($id);

      if ($student) {
          header('Content-Type: application/json');
          echo json_encode($student);
      } else {
          http_response_code(404);
          echo json_encode(['message' => 'Student not found.']);
      }
  }

  // Update the section_id of a student
  public function updateStudentSection($id)
  {
      $model = new Student();
      $sectionId = $_POST['section_id'] ?? null;

      if (!$sectionId) {
          http_response_code(400);
          echo json_encode(['success' => false, 'message' => 'Invalid section ID.']);
          return;
      }

      $success = $model->updateSection($id, $sectionId);

      if ($success) {
          echo json_encode(['success' => true, 'message' => 'Section updated successfully.']);
      } else {
          http_response_code(500);
          echo json_encode(['success' => false, 'message' => 'Failed to update section.']);
      }
  }

public function generateSamplePDF()
{
    try {
        // Fetch students from the database
        $model = new Student();
        $students = $model->getEnrolledStudents();

        // Create a new PDF instance
        $this->pdf = new FPDF('L', 'mm', 'A4'); // 'L' for landscape
        $this->pdf->AddPage();

        // Add a logo (replace 'path_to_logo.png' with your logo's path)
        $logoPath = 'views/assets/images/ccs-logo.jpg'; // Update this path to your logo file
        if (file_exists($logoPath)) {
            $this->pdf->Image($logoPath, 10, 6, 30); // X, Y, Width (height auto-calculated)
        }

        // Add university title
        $this->pdf->SetFont('Arial', 'B', 18);
        $this->pdf->Cell(0, 10, 'Angeles University Foundation', 0, 1, 'C');
        $this->pdf->SetFont('Arial', 'B', 16);
        $this->pdf->Cell(0, 10, 'College of Computer Studies', 0, 1, 'C');
        $this->pdf->Ln(10);

        // Add the document title
        $this->pdf->SetFont('Arial', 'B', 14);
        $this->pdf->Cell(0, 10, 'Student Information', 0, 1, 'C');
        $this->pdf->Ln(5);

        // Add a line below the title
        $this->pdf->SetLineWidth(0.5);
        $this->pdf->Line(10, 40, 287, 40); // Draw a horizontal line
        $this->pdf->Ln(10);

        // Add table headers
        $this->pdf->SetFont('Arial', 'B', 10);
        $this->pdf->Cell(40, 10, 'Complete Name', 1);
        $this->pdf->Cell(20, 10, 'Gender', 1);
        $this->pdf->Cell(60, 10, 'Email', 1);
        $this->pdf->Cell(30, 10, 'Mobile', 1);
        $this->pdf->Cell(60, 10, 'Address', 1);
        $this->pdf->Cell(20, 10, 'Year Level', 1);
        $this->pdf->Cell(40, 10, 'Program Code', 1);
        $this->pdf->Ln();

        // Populate the table rows
        $this->pdf->SetFont('Arial', '', 10);
        foreach ($students as $student) {
            $this->pdf->Cell(40, 10, $student['name'], 1);
            $this->pdf->Cell(20, 10, $student['gender'], 1);
            $this->pdf->Cell(60, 10, $student['email'], 1);
            $this->pdf->Cell(30, 10, $student['mobile'], 1);
            $this->pdf->Cell(60, 10, $student['address'], 1);
            $this->pdf->Cell(20, 10, $student['year_level'], 1);
            $this->pdf->Cell(40, 10, $student['program_code'], 1);
            $this->pdf->Ln();
        }

        // Output the PDF
        $this->pdf->Output('D', 'Student_Information.pdf'); // Force download
        exit;

    } catch (\Exception $e) {
        error_log('Error in generateSamplePDF: ' . $e->getMessage());
        echo 'Error: ' . $e->getMessage();
    }
}

public function recentPendingEnrollees()
{
    try {
        $model = new Student();
        $enrollees = $model->getRecentPendingEnrollees();

        header('Content-Type: application/json');
        echo json_encode($enrollees);
    } catch (\Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

public function removeSectionStudent($id)
{
    try {
        $studentModel = new Student();

        // Update the database to set section_id and student_id to NULL
        $result = $studentModel->removeStudentSection($id, ['section_id' => null, 'student_id' => null]);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Student and section successfully removed.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to remove student and section.']);
        }
    } catch (Exception $e) {
        error_log("Error removing student section: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'An unexpected error occurred.']);
    }
}

public function sendScheduleEmail()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $email = $data['email'];
        $scheduleHTML = $data['scheduleHTML'];

        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'donhenessydavid@gmail.com';
            $mail->Password = 'cwgp insv mwbf issu'; // Use an App Password if 2FA is enabled
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            

            // Recipients
            $mail->setFrom('your-email@example.com', 'College of Computer Studies');
            $mail->addAddress($email); // Recipient's email address

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Schedule for Section';
            $mail->Body = $scheduleHTML;

            $mail->send();

            echo json_encode(['success' => true, 'message' => 'Schedule sent successfully!']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to send schedule: ' . $mail->ErrorInfo]);
        }
    }
}

}