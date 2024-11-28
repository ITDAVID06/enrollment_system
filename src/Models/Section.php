<?php

namespace App\Models;

use App\Models\BaseModel;
use \PDO;

class Section extends BaseModel
{
    public function save($data)
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

        return ['row_count' => $statement->rowCount()];
    }

    public function getAllSections()
{
    $sql = "
        SELECT 
            sections.*, 
            programs.program_code, 
            COUNT(courses.id) AS course_count
        FROM sections
        LEFT JOIN programs ON sections.program_id = programs.id
        LEFT JOIN courses ON sections.program_id = courses.program_id
            AND sections.year_level = courses.year
            AND sections.semester = courses.semester
        GROUP BY sections.id
    ";
    
    $statement = $this->db->prepare($sql);
    $statement->execute();

    return $statement->fetchAll(PDO::FETCH_ASSOC);
}


    public function getSectionById($id)
    {
        $sql = "SELECT * FROM sections WHERE id = :id";
        $statement = $this->db->prepare($sql);
        $statement->execute(['id' => $id]);

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data)
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

    public function updateSchedule($courseId, $data)
    {
        // Prepare data for each day
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        $scheduleData = [];

        foreach ($days as $day) {
            if (in_array(ucfirst($day), $data['days'] ?? [])) {
                $scheduleData["{$day}_start"] = $data["{$day}_start"];
                $scheduleData["{$day}_end"] = $data["{$day}_end"];
            } else {
                $scheduleData["{$day}_start"] = null;
                $scheduleData["{$day}_end"] = null;
            }
        }

        // Update the schedule in the database
        $sql = "UPDATE schedules SET 
                    monday_start = :monday_start, monday_end = :monday_end,
                    tuesday_start = :tuesday_start, tuesday_end = :tuesday_end,
                    wednesday_start = :wednesday_start, wednesday_end = :wednesday_end,
                    thursday_start = :thursday_start, thursday_end = :thursday_end,
                    friday_start = :friday_start, friday_end = :friday_end
                WHERE course_id = :course_id";

        $statement = $this->db->prepare($sql);
        $scheduleData['course_id'] = $courseId;
        $statement->execute($scheduleData);

        return $statement->rowCount() > 0;
    }

    public function delete($id)
    {
        $sql = "DELETE FROM sections WHERE id = :id";
        $statement = $this->db->prepare($sql);
        return $statement->execute(['id' => $id]);
    }
}