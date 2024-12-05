<?php

namespace App\Models;

use App\Models\BaseModel;
use PDO;

class Schedule extends BaseModel
{
    public function save($data)
    {
        $sql = "INSERT INTO schedules (program_id, section_id, course_id, sched_semester)
                VALUES (:program_id, :section_id, :course_id, :sched_semester)";
        $statement = $this->db->prepare($sql);

        return $statement->execute([
            'program_id' => $data['program_id'],
            'section_id' => $data['section_id'],
            'course_id' => $data['course_id'],
            'sched_semester' => $data['sched_semester'],
        ]);
    }

    public function getCoursesByProgramSectionAndSemester($programId, $sectionId, $semester) 
    {
        $sql ="
            SELECT c.course_code, c.title, c.program_id
            FROM courses c
            LEFT JOIN sections s ON c.program_id = s.program_id
            AND c.semester = s.semester
            WHERE s.program_id = :programId
            AND s.id = :sectionId
            AND s.semester = :semester;  
            ;
        ";
        $statement = $this->db->prepare($sql);
        $statement->execute([
            ':program_id' => (int) $programId,
            ':section_id' => (int) $sectionId,
            ':semester' => (string) $semester
        ]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getProgramSections() 
    {
            $sql = "
                SELECT p.id AS program_id, s.id AS section_id, p.program_code, s.name AS section_name, s.year_level AS year
                FROM programs p
                JOIN sections s ON p.id = s.program_id
            ";
            $statement = $this->db->prepare($sql);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteSchedulesByCourseAndSection($courseId, $sectionId)
    {
        $sql = "
            DELETE FROM schedule 
            WHERE course_id = :course_id 
            AND section_id = :section_id
        ";
        $statement = $this->db->prepare($sql);
        $statement->execute([
            'course_id' => $courseId,
            'section_id' => $sectionId
        ]);

        return $statement->rowCount(); // Return the number of rows deleted
    }

    public function saveSchedule($scheduleData)
    {
        $sql = "
            INSERT INTO schedule (course_id, program_id, section_id, sched_day, TIME_FROM, TIME_TO, sched_semester, sched_sy, sched_room)
            VALUES (:course_id, :program_id, :section_id, :sched_day, :TIME_FROM, :TIME_TO, :sched_semester, :sched_sy, :sched_room)
            ON DUPLICATE KEY UPDATE 
                TIME_FROM = VALUES(TIME_FROM),
                TIME_TO = VALUES(TIME_TO),
                sched_semester = VALUES(sched_semester),
                sched_sy = VALUES(sched_sy),
                sched_room = VALUES(sched_room)
        ";
        
        $statement = $this->db->prepare($sql);

        return $statement->execute([
            'course_id' => $scheduleData['course_id'],
            'program_id' => $scheduleData['program_id'],
            'section_id' => $scheduleData['section_id'],
            'sched_day' => $scheduleData['sched_day'],
            'TIME_FROM' => $scheduleData['TIME_FROM'],
            'TIME_TO' => $scheduleData['TIME_TO'],
            'sched_semester' => $scheduleData['sched_semester'],
            'sched_sy' => $scheduleData['sched_sy'],
            'sched_room' => $scheduleData['sched_room']
        ]);
    }


}
