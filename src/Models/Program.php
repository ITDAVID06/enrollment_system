<?php

namespace App\Models;

use App\Models\BaseModel;
use \PDO;

class Program extends BaseModel
{
    public function save($data)
    {
        $sql = "INSERT INTO programs
                SET program_code = :program_code,
                    title = :title,
                    years = :years";
        $statement = $this->db->prepare($sql);

        $statement->execute([
            'program_code' => $data['program_code'],
            'title' => $data['title'],
            'years' => $data['years'],
        ]);

        $lastInsertId = $this->db->lastInsertId();

        return [
            'row_count' => $statement->rowCount(),
            'last_insert_id' => $lastInsertId
        ];
    }

    public function getAllPrograms()
    {
        $sql = "SELECT * FROM programs";
        $statement = $this->db->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProgramById($id)
    {
        $sql = "SELECT * FROM programs WHERE id = :id";
        $statement = $this->db->prepare($sql);
        $statement->execute(['id' => $id]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE programs 
                SET program_code = :program_code,
                    title = :title,
                    years = :years
                WHERE id = :id";

        $params = [
            'id' => $id,
            'program_code' => $data['program_code'],
            'title' => $data['title'],
            'years' => $data['years'],
        ];

        $statement = $this->db->prepare($sql);
        return $statement->execute($params);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM programs WHERE id = :id";
        $statement = $this->db->prepare($sql);
        return $statement->execute(['id' => $id]);
    }
}
