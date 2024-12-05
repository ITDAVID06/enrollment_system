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
            'complete_name' => $_SESSION['complete_name'] ?? '',
            'email' => $_SESSION['email'] ?? '',
            'recentEnrollees' => $recentEnrollees ?? [],
            'programTotals' => $programTotals ?? [] // Ensure it defaults to an empty array
        ];
        return $this->render('root', $data);
    }

    public function getChartData()
{
    try {
        $model = new Student();

        $genderData = $model->getGenderDistribution();
        $programData = $model->getProgramDistribution();
        $yearLevelData = $model->getYearLevelBreakdown();

        $data = [
            'gender' => $genderData,
            'program' => $programData,
            'yearLevel' => $yearLevelData,
        ];

        header('Content-Type: application/json');
        echo json_encode($data);
    } catch (\Exception $e) {
        error_log('Error in getChartData: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'Failed to fetch chart data.']);
    }
}

}
