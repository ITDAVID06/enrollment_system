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

    public function save($data)
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

    public function getAll()
    {
        $sql = "SELECT * FROM enrollees";
        $statement = $this->db->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM enrollees WHERE id = :id";
        $statement = $this->db->prepare($sql);
        $statement->execute(['id' => $id]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE enrollees 
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
                    uploaded_grade_file = :uploaded_grade_file
                WHERE id = :id";
        
        $statement = $this->db->prepare($sql);
        $params = [
            'id' => $id,
            'last_name' => $data['last_name'] ?? null,
            'first_name' => $data['first_name'] ?? null,
            'middle_name' => $data['middle_name'] ?? null,
            'gender' => $data['gender'] ?? null,
            'email' => $data['email'] ?? null,
            'contact_mobile' => $data['contact_mobile'] ?? null,
            'street_address' => $data['street_address'] ?? null,
            'program_applying_for' => $data['program_applying_for'] ?? null,
            'year_level' => $data['year_level'] ?? null,
            'term' => $data['term'] ?? null,
            'uploaded_grade_file' => $data['uploaded_grade_file'] ?? null,
        ];
        return $statement->execute($params);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM enrollees WHERE id = :id";
        $statement = $this->db->prepare($sql);
        return $statement->execute(['id' => $id]);
    }

}