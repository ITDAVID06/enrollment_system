<?php

namespace App\Models;

use App\Models\BaseModel;
use \PDO;

class StudentInformation extends BaseModel
{
    public function getPrograms() 
    {
        $sql = "SELECT id, title FROM programs";
        return $this->fetchAll($sql);
    }

    public function saveStudentInformation($data)
    {
        // SQL query for inserting student information
        $sql = "INSERT INTO applicant
                SET
                    lastname = :lastname,
                    firstname = :firstname,
                    middlename = :middlename,
                    gender = :gender,
                    date_of_birth = :date_of_birth,
                    place_of_birth = :place_of_birth,
                    contact_mobile = :contact_mobile,
                    email = :email,
                    profile_picture_path = :profile_picture_path,
                    emergency_contact_name = :emergency_contact_name,
                    emergency_contact_mobile = :emergency_contact_mobile,
                    province = :province,
                    city = :city,
                    barangay = :barangay,
                    street_address = :street_address,
                    zipcode = :zipcode,
                    elementary_school = :elementary_school,
                    high_school = :high_school,
                    course = :course,
                    grade_file_path = :grade_file_path";

        // Prepare the SQL statement using the inherited $db property from BaseModel
        $stmt = $this->db->prepare($sql);

        // Bind the parameters
        $params = [
            ':lastname' => $data['lastname'],
            ':firstname' => $data['firstname'],
            ':middlename' => $data['middlename'] ?? null,
            ':gender' => $data['gender'],
            ':date_of_birth' => $data['date_of_birth'],
            ':place_of_birth' => $data['place_of_birth'],
            ':contact_mobile' => $data['contact_mobile'],
            ':email' => $data['email'],
            ':profile_picture_path' => $data['profile_picture_path'],
            ':emergency_contact_name' => $data['emergency_contact_name'],
            ':emergency_contact_mobile' => $data['emergency_contact_mobile'],
            ':province' => $data['province'],
            ':city' => $data['city'],
            ':barangay' => $data['barangay'],
            ':street_address' => $data['street_address'],
            ':zipcode' => $data['zipcode'] ?? null,
            ':elementary_school' => $data['elementary_school'],
            ':high_school' => $data['high_school'],
            ':course' => $data['course'],
            ':grade_file_path' => $data['grade_file_path']
        ];

        // Execute the statement with the provided parameters
        $stmt->execute($params);

        // Retrieve the last inserted ID to return if necessary
        $lastInsertId = $this->db->lastInsertId();

        return [
            'row_count' => $stmt->rowCount(),
            'last_insert_id' => $lastInsertId
        ];
    }

     /**
     * Retrieve All Student Information
     * @return array List of all applicants
     */

    public function getAllStudentInformation()
    {
        $sql = "SELECT * FROM applicant";
        return $this->fetchAll($sql);
    }
}