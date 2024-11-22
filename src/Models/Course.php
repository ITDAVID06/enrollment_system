<?php

namespace App\Models;

use App\Models\BaseModel;
use \PDO;

class Course extends BaseModel
{
    public function save($data)
    {
        $sql = "INSERT INTO courses 
                SET program_id = :program_id,
                    course_code = :course_code,
                    title = :title,
                    unit = :unit,
                    semester = :semester,
                    year = :year";
        $statement = $this->db->prepare($sql);

        $statement->execute([
            'program_id' => $data['program_id'],
            'course_code' => $data['course_code'],
            'title' => $data['title'],
            'unit' => $data['unit'],
            'semester' => $data['semester'],
            'year' => $data['year'],
        ]);

        return ['row_count' => $statement->rowCount()];
    }

    public function getCoursesByProgramAndYear($programId, $year)
    {
        $sql = "SELECT * FROM courses WHERE program_id = :program_id AND year = :year";
        $statement = $this->db->prepare($sql);
        $statement->execute(['program_id' => $programId, 'year' => $year]);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCourseById($id)
{
    $sql = "
        SELECT 
            courses.*, 
            schedules.*
        FROM courses
        LEFT JOIN schedules ON courses.id = schedules.course_id
        WHERE courses.id = :id
    ";
    $statement = $this->db->prepare($sql);
    $statement->execute(['id' => $id]);

    return $statement->fetch(PDO::FETCH_ASSOC);
}

    public function update($id, $data)
    {
        $sql = "UPDATE courses 
                SET course_code = :course_code,
                    title = :title,
                    unit = :unit,
                    semester = :semester,
                    year = :year
                WHERE id = :id";
        $statement = $this->db->prepare($sql);

        return $statement->execute([
            'id' => $id,
            'course_code' => $data['course_code'],
            'title' => $data['title'],
            'unit' => $data['unit'],
            'semester' => $data['semester'],
            'year' => $data['year'],
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM courses WHERE id = :id";
        $statement = $this->db->prepare($sql);
        return $statement->execute(['id' => $id]);
    }

    // public function getCourses()
    // {
    //     $sql = "
    //         SELECT 
    //             courses.id AS course_id, 
    //             courses.course_code AS course_code, 
    //             schedule.TIME_FROM, 
    //             schedule.TIME_TO, 
    //             schedule.sched_day, 
    //             schedule.sched_semester, 
    //             schedule.sched_sy, 
    //             schedule.sched_room, 
    //             schedule.section_id, 
    //             schedule.PROGRAM_ID 
    //         FROM courses 
    //         LEFT JOIN schedule ON courses.id = schedule.COURSE_ID
    //     ";
    
    //     $statement = $this->db->prepare($sql);
    
    //     // Execute the query
    //     if ($statement->execute()) {
    //         // Fetch and return results
    //         return $statement->fetchAll(PDO::FETCH_ASSOC);
    //     } else {
    //         // Log and return false if execution fails
    //         error_log("Query Execution Failed: " . print_r($statement->errorInfo(), true));
    //         return false;
    //     }
    // }

    public function getCourses($section_id)
{
    $sql = "
        SELECT 
            courses.id AS course_id, 
            courses.course_code, 
            schedule.TIME_FROM, 
            schedule.TIME_TO, 
            schedule.sched_day 
        FROM courses 
        LEFT JOIN schedule ON courses.id = schedule.COURSE_ID 
        WHERE schedule.section_id = :section_id
    ";

    $statement = $this->db->prepare($sql);
    if ($statement->execute(['section_id' => $section_id])) {
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    } else {
        error_log("Query Execution Failed: " . print_r($statement->errorInfo(), true));
        return false;
    }
}

    

}
