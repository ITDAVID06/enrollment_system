<?php

namespace App\Models;

use App\Models\BaseModel;
use PDO;

class Enrollee extends BaseModel
{
    public function getAll()
    {
        $sql = "SELECT * FROM enrollees WHERE section_id IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM enrollees WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getLatestStudentId()
{
    $sql = "SELECT student_id FROM enrollees WHERE student_id IS NOT NULL ORDER BY student_id DESC LIMIT 1";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetch(\PDO::FETCH_ASSOC);
}

public function isStudentIdUsed($studentId)
{
    $sql = "SELECT COUNT(*) FROM enrollees WHERE student_id = :student_id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['student_id' => $studentId]);
    return $stmt->fetchColumn() > 0;
}

public function setEnrollment($id, $studentId, $sectionId)
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




    public function update($id, $data)
    {
        $sql = "UPDATE enrollees SET last_name = :last_name, first_name = :first_name, email = :email, contact_mobile = :contact_mobile WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM enrollees WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    
}
