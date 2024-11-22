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

    public function updateSchedule($courseId, $data)
    {
        $sql = "UPDATE schedules SET 
                    monday_start = :monday_start, monday_end = :monday_end,
                    tuesday_start = :tuesday_start, tuesday_end = :tuesday_end,
                    wednesday_start = :wednesday_start, wednesday_end = :wednesday_end,
                    thursday_start = :thursday_start, thursday_end = :thursday_end,
                    friday_start = :friday_start, friday_end = :friday_end
                WHERE course_id = :course_id";

        $statement = $this->db->prepare($sql);
        $data['course_id'] = $courseId;

        return $statement->execute($data);
    }
}
