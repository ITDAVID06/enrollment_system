<?php

namespace App\Models;

use App\Models\BaseModel;
use PDO;

class Enrollee extends BaseModel
{
    public function getAllEnrollees()
    {
        $sql = "SELECT * FROM enrollees WHERE section_id IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getByEnrolleeId($id)
    {
        $sql = " SELECT e.*, p.program_code AS program
                FROM enrollees e
                LEFT JOIN programs p ON p.id = e.program_applying_for
                WHERE e.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function enlistEnrollee($id, $studentId, $sectionId)
    {
        $sql = "UPDATE enrollees 
                SET student_id = :student_id, 
                    section_id = :section_id 
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'student_id' => $studentId,
            'section_id' => $sectionId,
            'id' => $id
        ]);
    }

    public function updateEnrollee($id, $data)
    {
        $sql = "UPDATE enrollees SET last_name = :last_name, first_name = :first_name, email = :email, contact_mobile = :contact_mobile WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public function deleteEnrollee($id)
    {
        $sql = "DELETE FROM enrollees WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
