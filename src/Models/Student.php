<?php

namespace App\Models;

use App\Models\BaseModel;

class Student extends BaseModel
{
    // Fetch all students with section_id and student_id
    public function getEnrolledStudents()
    {
        $sql = "SELECT 
                    enrollees.id,
                    CONCAT(enrollees.last_name, ', ', enrollees.first_name) AS name,
                    enrollees.gender,
                    enrollees.section_id,
                    enrollees.student_id AS student_id,
                    enrollees.email,
                    enrollees.year_level,
                    enrollees.program_applying_for AS program_id,
                    sections.name AS section_name,
                    programs.program_code AS program_code,
                    programs.title AS program_name,
                    enrollees.year_level,
                    enrollees.term AS semester,
                    enrollees.contact_mobile AS mobile,
                    enrollees.street_address AS address
                FROM enrollees
                INNER JOIN sections ON enrollees.section_id = sections.id
                INNER JOIN programs ON sections.program_id = programs.id
                WHERE enrollees.student_id IS NOT NULL 
                  AND enrollees.section_id IS NOT NULL";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    // Fetch details of a single student by ID
    public function getStudentById($id)
    {
        $sql = "SELECT * FROM enrollees WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // Update the section_id of a student
    public function updateSection($id, $sectionId)
    {
        $sql = "UPDATE enrollees SET section_id = :section_id WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['section_id' => $sectionId, 'id' => $id]);
    }
    
    public function getGenderDistribution()
    {
        $sql = "SELECT gender, COUNT(*) as count 
                FROM enrollees 
                WHERE student_id IS NOT NULL AND section_id IS NOT NULL 
                GROUP BY gender";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Program Distribution
    public function getProgramDistribution()
    {
        $sql = "SELECT 
                    programs.program_code, 
                    COUNT(*) as count 
                FROM enrollees
                INNER JOIN programs ON enrollees.program_applying_for = programs.id
                WHERE student_id IS NOT NULL AND section_id IS NOT NULL 
                GROUP BY programs.program_code";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    // Year Level Breakdown
    public function getYearLevelBreakdown()
    {
        $sql = "SELECT year_level, COUNT(*) as count 
                FROM enrollees 
                WHERE student_id IS NOT NULL AND section_id IS NOT NULL 
                GROUP BY year_level";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getRecentPendingEnrollees()
    {
        $sql = "SELECT 
                    CONCAT(e.last_name, ', ', e.first_name) AS name,
                    p.program_code AS program_code,
                    e.year_level
                FROM enrollees e
                INNER JOIN programs p ON e.program_applying_for = p.id
                WHERE e.student_id IS NULL AND e.section_id IS NULL
                ORDER BY e.created_at DESC
                LIMIT 5";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getTotalStudentsByProgram()
    {
        $sql = "SELECT 
                    programs.program_code, 
                    COUNT(*) as total_students 
                FROM enrollees
                INNER JOIN sections ON enrollees.section_id = sections.id
                INNER JOIN programs ON sections.program_id = programs.id
                WHERE enrollees.student_id IS NOT NULL AND enrollees.section_id IS NOT NULL
                GROUP BY programs.program_code";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function removeStudentSection($studentId, $data)
    {
        $sql = "UPDATE enrollees SET section_id = :section_id, student_id = :student_id WHERE id = :id";
        $statement = $this->db->prepare($sql);
        return $statement->execute([
            ':section_id' => $data['section_id'],
            ':student_id' => $data['student_id'],
            ':id' => $studentId,
        ]);
    }

}
