<?php

namespace App\Models;

use App\Models\BaseModel;
use \PDO;

class StudentRegistration extends BaseModel
{
    public function getPrograms() 
    {
        $sql = "SELECT id, title FROM programs";
        return $this->fetchAll($sql);
    }

    public function saveRegistration($data) {
        $sql = "INSERT INTO registration 
                SET
                    status = :status,
                    previous_school = :previous_school,
                    program_id = :program_id,
                    year_level = :year_level,
                    term = :term,
                    school_year = :school_year,
                    created_at = NOW()";
    
        $statement = $this->db->prepare($sql);
        $statement->execute([
            'status' => $data['status'],
            'previous_school' => $data['previous_school'],
            'program_id' => $data['program_id'],
            'year_level' => $data['year_level'],
            'term' => $data['term'],
            'school_year' => $data['school_year']
        ]);
    
        $lastInsertId = $this->db->lastInsertId();
    
        return [
            'row_count' => $statement->rowCount(),
            'last_insert_id' => $lastInsertId
        ];
    }    
}