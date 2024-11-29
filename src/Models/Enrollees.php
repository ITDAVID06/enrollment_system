<?php

namespace App\Models;

use App\Models\BaseModel;
use \PDO;

class Enrollees extends BaseModel
{
    public function getAllEnrolleesWithDetails()
    {
        $sql = "
            SELECT e.id, 
                   CONCAT(e.first_name, ' ', e.last_name) AS name, 
                   e.program_applying_for, 
                   e.year_level, 
                   e.student_status, 
                   e.student_id
            FROM enrollee e
        ";
        $statement = $this->db->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEnrolleesById($id)
    {
        $sql = "
            SELECT e.*, 
                   e.program_applying_for
            FROM enrollee e
            WHERE e.id = :id
        ";
        $statement = $this->db->prepare($sql);
        $statement->execute(['id' => $id]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function updateEnrollee($id, $data)
    {
        $sql = "UPDATE enrollee 
                SET student_status = :student_status,
                    student_id = :student_id
                WHERE id = :id";

        $params = [
            'id' => $id,
            'student_status' => $data['student_status'],
            'student_id' => $data['student_id'],
        ];

        $statement = $this->db->prepare($sql);
        return $statement->execute($params);
    }
}
