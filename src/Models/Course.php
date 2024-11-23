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
            SELECT * 
            FROM courses
            WHERE id = :id
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

    public function getCourses($section_id)
{
    $sql = "
        SELECT 
            courses.id AS course_id,
            courses.program_id, 
            courses.course_code, 
            schedule.TIME_FROM, 
            schedule.TIME_TO, 
            schedule.sched_day,
            schedule.PROGRAM_ID AS schedule_program_id,
            schedule.COURSE_ID AS schedule_course_id
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

public function getCoursesWithSchedules($program_id, $year, $semester, $section)
{
    $sql = "
        SELECT 
            courses.id AS course_id,
            courses.course_code,
            schedule.TIME_FROM,
            schedule.TIME_TO,
            schedule.sched_day,
            schedule.sched_room
        FROM courses
        LEFT JOIN schedule ON courses.id = schedule.COURSE_ID
        WHERE courses.program_id = :program_id
        AND courses.year = :year
        AND courses.semester = :semester
        AND (schedule.section_id = :section OR schedule.section_id IS NULL)
    ";

    $statement = $this->db->prepare($sql);
    $statement->execute([
        'program_id' => $program_id,
        'year' => $year,
        'semester' => $semester,
        'section' => $section
    ]);

    return $statement->fetchAll(PDO::FETCH_ASSOC);
}


}
