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

    public function saveEnrollee($data)
    {
        $sql = "INSERT INTO enrollees 
                SET
                    last_name = :last_name,
                    first_name = :first_name,
                    middle_name = :middle_name,
                    gender = :gender,
                    email = :email,
                    contact_mobile = :contact_mobile,
                    street_address = :street_address,
                    program_applying_for = :program_applying_for,
                    year_level = :year_level,
                    term = :term,
                    uploaded_grade_file = :uploaded_grade_file";
        
        $statement = $this->db->prepare($sql);
        $statement->execute([
            'last_name' => $data['last_name'],
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'gender' => $data['gender'],
            'email' => $data['email'],
            'contact_mobile' => $data['contact_mobile'],
            'street_address' => $data['street_address'],
            'program_applying_for' => $data['program_applying_for'],
            'year_level' => $data['year_level'],
            'term' => $data['term'],
            'uploaded_grade_file' => $data['uploaded_grade_file'],
        ]);

        $lastInsertId = $this->db->lastInsertId();

        return [
            'row_count' => $statement->rowCount(),
            'last_insert_id' => $lastInsertId,
        ];
    }

}