<?php

namespace App\Controllers;

use App\Models\Form;
use App\Controllers\BaseController;

class FormController extends BaseController
{
    public function showForm()
    {
        $form = new Form();

        // Retrieve dropdown data
        $data = [
            'title' => 'Enrollment System',
            'statuses' => $form->getStatuses(),
            'schools' => $form->getSchools(),
            'programs' => $form->getPrograms(),
            'year_levels' => $form->getYearLevels(),
            'terms' => $form->getTerms(),
            'school_years' => $form->getSchoolYears(),
        ];

        return $this->render('form', $data);
    }

    public function submitEnrollment()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            $form = new Form();
            $result = $form->save($data);

            if ($result['row_count'] > 0) {
                $_SESSION['success'] = 'Enrollment submitted successfully!';
            } else {
                $_SESSION['error'] = 'Failed to submit enrollment. Please try again.';
            }

            return $this->showForm();  // Redirect back to the form view
        }
    }
}
