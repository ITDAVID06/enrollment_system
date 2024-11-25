<?php

namespace App\Models;

use App\Models\BaseModel;
use PDO;

class Schedule extends BaseModel
{
    public function getCoursesBySection($section_id)
    {
        $sql = "
            SELECT 
                courses.*, 
                schedules.monday_start, schedules.monday_end, 
                schedules.tuesday_start, schedules.tuesday_end,
                schedules.wednesday_start, schedules.wednesday_end,
                schedules.thursday_start, schedules.thursday_end,
                schedules.friday_start, schedules.friday_end
            FROM courses
            LEFT JOIN schedules ON courses.id = schedules.course_id
            WHERE courses.section_id = :section_id
        ";

        $statement = $this->db->prepare($sql);
        $statement->execute(['section_id' => $sectionId]);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

   public function getScheduleByCourseId($courseId)
{
    $sql = "
        SELECT 
            c.id,
            c.course_code,
            c.title,
            p.id,
            p.title
        FROM courses c
        INNER JOIN programs p ON c.program_id = p.id
        WHERE c.id = :course_id
    ";
    $statement = $this->db->prepare($sql);
    $statement->execute(['course_id' => $courseId]);
    
    return $statement->fetchAll(PDO::FETCH_ASSOC); // Returns an array of course and program details
}
    


    public function addSchedule($data)
{
    $sql = "
        INSERT INTO schedule (
            TIME_FROM, TIME_TO, sched_day, sched_semester, sched_sy, sched_room, SECTION, PROGRAM_ID, COURSE_ID
        ) VALUES (
            :TIME_FROM, :TIME_TO, :sched_day, :sched_semester, :sched_sy, :sched_room, :SECTION, :PROGRAM_ID, :COURSE_ID
        )
    ";

    $statement = $this->db->prepare($sql);
    return $statement->execute([
        'TIME_FROM' => $data['TIME_FROM'],
        'TIME_TO' => $data['TIME_TO'],
        'sched_day' => $data['sched_day'],
        'sched_semester' => $data['sched_semester'],
        'sched_sy' => $data['sched_sy'],
        'sched_room' => $data['sched_room'],
        'SECTION' => $data['SECTION'],
        'PROGRAM_ID' => $data['PROGRAM_ID'],
        'COURSE_ID' => $data['COURSE_ID']
    ]);
}

public function updateSchedule($courseCode, $data)
{
    $sql = "
        UPDATE schedule SET 
            TIME_FROM = :TIME_FROM,
            TIME_TO = :TIME_TO,
            sched_day = :sched_day,
            sched_semester = :sched_semester,
            sched_sy = :sched_sy,
            sched_room = :sched_room
        WHERE COURSE_ID = (
            SELECT id FROM courses WHERE course_code = :course_code
        )
    ";

    $statement = $this->db->prepare($sql);
    return $statement->execute([
        'TIME_FROM' => $data['TIME_FROM'],
        'TIME_TO' => $data['TIME_TO'],
        'sched_day' => $data['sched_day'],
        'sched_semester' => $data['sched_semester'],
        'sched_sy' => $data['sched_sy'],
        'sched_room' => $data['sched_room'],
        'course_code' => $courseCode
    ]);
}

public function getScheduleByCourseCode($courseCode)
{
    $sql = "
        SELECT 
            courses.course_code,
            courses.id AS course_id,
            schedule.TIME_FROM,
            schedule.TIME_TO,
            schedule.sched_day,
            schedule.sched_semester,
            schedule.sched_sy,
            schedule.sched_room
        FROM courses
        LEFT JOIN schedule ON courses.id = schedule.COURSE_ID
        WHERE courses.course_code = :course_code
    ";

    $statement = $this->db->prepare($sql);
    $statement->execute(['course_code' => $courseCode]);

    // Fetch the schedule or course details (even if no schedule exists)
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        // If no matching course is found, throw an error or return empty structure
        return [
            "course_code" => $courseCode,
            "course_id" => null,
            "TIME_FROM" => null,
            "TIME_TO" => null,
            "sched_day" => null,
            "sched_semester" => null,
            "sched_sy" => null,
            "sched_room" => null,
        ];
    }

    return $result;
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

public function saveOrUpdateSchedule($data)
{
    // Check if a schedule already exists for this course and day
    $sqlCheck = "
        SELECT schedID 
        FROM schedule 
        WHERE COURSE_ID = :course_id AND sched_day = :sched_day
    ";
    $stmtCheck = $this->db->prepare($sqlCheck);
    $stmtCheck->execute([
        'course_id' => $data['course_id'],
        'sched_day' => $data['sched_day']
    ]);
    $existingSchedule = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if ($existingSchedule) {
        // Update existing schedule
        $sqlUpdate = "
            UPDATE schedule SET 
                TIME_FROM = :TIME_FROM,
                TIME_TO = :TIME_TO,
                sched_semester = :sched_semester,
                sched_sy = :sched_sy,
                sched_room = :sched_room
            WHERE schedID = :schedID
        ";
        $stmtUpdate = $this->db->prepare($sqlUpdate);
        $stmtUpdate->execute([
            'TIME_FROM' => $data['TIME_FROM'],
            'TIME_TO' => $data['TIME_TO'],
            'sched_semester' => $data['sched_semester'],
            'sched_sy' => $data['sched_sy'],
            'sched_room' => $data['sched_room'],
            'schedID' => $existingSchedule['schedID']
        ]);
    } else {
        // Insert new schedule
        $sqlInsert = "
            INSERT INTO schedule (
                COURSE_ID, PROGRAM_ID, TIME_FROM, TIME_TO, sched_day, sched_semester, sched_sy, sched_room
            ) VALUES (
                :course_id, :program_id, :TIME_FROM, :TIME_TO, :sched_day, :sched_semester, :sched_sy, :sched_room
            )
        ";
        $stmtInsert = $this->db->prepare($sqlInsert);
        $stmtInsert->execute([
            'course_id' => $data['course_id'],
            'program_id' => $data['program_id'],
            'TIME_FROM' => $data['TIME_FROM'],
            'TIME_TO' => $data['TIME_TO'],
            'sched_day' => $data['sched_day'],
            'sched_semester' => $data['sched_semester'],
            'sched_sy' => $data['sched_sy'],
            'sched_room' => $data['sched_room']
        ]);
    }
}






}
