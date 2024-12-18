<?php

namespace App\Models;

use App\Models\BaseModel;
use \PDO;

class Section extends BaseModel
{
    public function saveSection($data)
    {
        $sql = "INSERT INTO sections (program_id, name, semester, year_level) 
                VALUES (:program_id, :name, :semester, :year_level)";
        $statement = $this->db->prepare($sql);

        $statement->execute([
            'program_id' => $data['program_id'],
            'name' => $data['name'],
            'semester' => $data['semester'],
            'year_level' => $data['year_level'],
        ]);

        $lastInsertId = $this->db->lastInsertId();

        return [
            'row_count' => $statement->rowCount(),
            'last_insert_id' => $lastInsertId
        ];
    }

    public function getAllSections()
    {
        $sql = "
        SELECT 
        sections.id,
        sections.name,
        sections.year_level,
        sections.semester,
        sections.program_id,
        programs.program_code,
        COUNT(courses.id) AS course_count
        FROM sections
        LEFT JOIN programs 
            ON sections.program_id = programs.id
        LEFT JOIN courses 
            ON courses.program_id = sections.program_id
            AND courses.year = sections.year_level
            AND courses.semester = sections.semester
        GROUP BY sections.id, sections.name, sections.year_level, sections.semester, sections.program_id, programs.program_code;

        ";
        
        $statement = $this->db->prepare($sql);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSections()
    {
        $sql = "SELECT 
                    sections.id, 
                    CONCAT(programs.program_code, ' - ', sections.name) AS name,
                    sections.year_level AS year
                FROM sections
                INNER JOIN programs ON sections.program_id = programs.id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getSectionById($id)
    {
        $sql = "SELECT * FROM sections WHERE id = :id";
        $statement = $this->db->prepare($sql);
        $statement->execute(['id' => $id]);

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function updateSection($id, $data)
    {
        $sql = "UPDATE sections 
                SET program_id = :program_id, 
                    name = :name, 
                    semester = :semester, 
                    year_level = :year_level
                WHERE id = :id";
        $statement = $this->db->prepare($sql);

        return $statement->execute([
            'id' => $id,
            'program_id' => $data['program_id'],
            'name' => $data['name'],
            'semester' => $data['semester'],
            'year_level' => $data['year_level'],
        ]);
    }


    public function deleteSection($id)
    {
        $sql = "DELETE FROM sections WHERE id = :id";
        $statement = $this->db->prepare($sql);
        return $statement->execute(['id' => $id]);
    }
}
