<?php

namespace App\Models;

use App\Models\BaseModel;
use \PDO;

class Form extends BaseModel
{


    public function getStatuses() {
        $sql = "SELECT id, name FROM statuses";
        return $this->fetchAll($sql);
    }

    public function getSchools() {
        $sql = "SELECT id, name FROM schools";
        return $this->fetchAll($sql);
    }

    public function getPrograms() {
        $sql = "SELECT id, name FROM programs";
        return $this->fetchAll($sql);
    }

    public function getYearLevels() {
        $sql = "SELECT id, level FROM year_levels";
        return $this->fetchAll($sql);
    }

    public function getTerms() {
        $sql = "SELECT id, name FROM terms";
        return $this->fetchAll($sql);
    }

    public function getSchoolYears() {
        $sql = "SELECT id, year FROM school_years";
        return $this->fetchAll($sql);
    }

    // Save new enrollment data
    public function save($data)
    {
        $sql = "INSERT INTO enrollments 
                SET
                    status_id = :status_id,
                    school_id = :school_id,
                    program_id = :program_id,
                    year_level_id = :year_level_id,
                    term_id = :term_id,
                    school_year_id = :school_year_id,
                    created_at = NOW()";

        $statement = $this->db->prepare($sql);
        $statement->execute([
            'status_id' => $data['status_id'],
            'school_id' => $data['school_id'],
            'program_id' => $data['program_id'],
            'year_level_id' => $data['year_level_id'],
            'term_id' => $data['term_id'],
            'school_year_id' => $data['school_year_id']
        ]);

        $lastInsertId = $this->db->lastInsertId();

        return [
            'row_count' => $statement->rowCount(),
            'last_insert_id' => $lastInsertId
        ];
    }

    // Retrieve all enrollment records
    public function getAllEnrollments()
    {
        $sql = "SELECT enrollments.*, statuses.name AS status, schools.name AS school, 
                       programs.name AS program, year_levels.level AS year_level, 
                       terms.name AS term, school_years.year AS school_year
                FROM enrollments
                JOIN statuses ON enrollments.status_id = statuses.id
                JOIN schools ON enrollments.school_id = schools.id
                JOIN programs ON enrollments.program_id = programs.id
                JOIN year_levels ON enrollments.year_level_id = year_levels.id
                JOIN terms ON enrollments.term_id = terms.id
                JOIN school_years ON enrollments.school_year_id = school_years.id";
        
        $statement = $this->db->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
