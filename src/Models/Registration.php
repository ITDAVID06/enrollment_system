<?php

namespace App\Models;

use App\Models\BaseModel;
use \PDO;

class Registration extends BaseModel
{
    public function getPrograms() 
    {
        $sql = "SELECT id, title FROM programs";
        return $this->fetchAll($sql);
    }


    public function saveRegistration($data)
    {
        // Set default values for optional fields
        $data = array_merge([
            'student_id' => null,
            'student_status' => 'Pending',
            'section_id' => null,
            'program_id' => null,
            'sched_id' => null,
        ], $data);

        $sql = "INSERT INTO enrollee 
                SET
                    student_id = :student_id,
                    student_status = :student_status,
                    section_id = :section_id,
                    program_id = :program_id,
                    sched_id = :sched_id,
                    status_of_enrollee = :status_of_enrollee,
                    previous_school = :previous_school,
                    program_applying_for = :program_applying_for,
                    year_level = :year_level,
                    term = :term,
                    school_year = :school_year,
                    last_name = :last_name,
                    first_name = :first_name,
                    middle_name = :middle_name,
                    gender = :gender,
                    date_of_birth = :date_of_birth,
                    place_of_birth = :place_of_birth,
                    contact_mobile = :contact_mobile,
                    email = :email,
                    citizenship = :citizenship,
                    first_gen_student = :first_gen_student,
                    emergency_contact_name = :emergency_contact_name,
                    emergency_contact_mobile = :emergency_contact_mobile,
                    street_address = :street_address,
                    province = :province,
                    city_municipality = :city_municipality,
                    barangay = :barangay,
                    zip_code = :zip_code,
                    elementary_school = :elementary_school,
                    junior_high_school = :junior_high_school,
                    senior_high_school = :senior_high_school,
                    shs_track = :shs_track,
                    uploaded_grade_file = :uploaded_grade_file,
                    created_at = NOW()";

        $statement = $this->db->prepare($sql);
        $statement->execute([
            'student_id' => $data['student_id'],
            'student_status' => $data['student_status'],
            'section_id' => $data['section_id'],
            'program_id' => $data['program_id'],
            'sched_id' => $data['sched_id'],
            'status_of_enrollee' => $data['status_of_enrollee'],
            'previous_school' => $data['previous_school'],
            'program_applying_for' => $data['program_applying_for'],
            'year_level' => $data['year_level'],
            'term' => $data['term'],
            'school_year' => $data['school_year'],
            'last_name' => $data['last_name'],
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'],
            'gender' => $data['gender'],
            'date_of_birth' => $data['date_of_birth'],
            'place_of_birth' => $data['place_of_birth'],
            'contact_mobile' => $data['contact_mobile'],
            'email' => $data['email'],
            'citizenship' => $data['citizenship'],
            'first_gen_student' => $data['first_gen_student'],
            'emergency_contact_name' => $data['emergency_contact_name'],
            'emergency_contact_mobile' => $data['emergency_contact_mobile'],
            'street_address' => $data['street_address'],
            'province' => $data['province'],
            'city_municipality' => $data['city_municipality'],
            'barangay' => $data['barangay'],
            'zip_code' => $data['zip_code'],
            'elementary_school' => $data['elementary_school'],
            'junior_high_school' => $data['junior_high_school'],
            'senior_high_school' => $data['senior_high_school'],
            'shs_track' => $data['shs_track'],
            'uploaded_grade_file' => $data['uploaded_grade_file']
        ]);

        $lastInsertId = $this->db->lastInsertId();

        return [
            'row_count' => $statement->rowCount(),
            'last_insert_id' => $lastInsertId
        ];
    }

    public function emailExists($email)
    {
        $sql = "SELECT id FROM enrollee WHERE email = :email";
        $statement = $this->db->prepare($sql);
        $statement->execute(['email' => $email]);
        return $statement->fetch(PDO::FETCH_ASSOC); // Returns true if a record exists, false otherwise
    }

}