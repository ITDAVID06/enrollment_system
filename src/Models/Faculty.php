<?php

namespace App\Models;

use App\Models\BaseModel;
use \PDO;

class Faculty extends BaseModel
{
    public function getDbConnection()
    {
        return $this->db;
    }
    
    public function save($data) {
        $sql = "INSERT INTO faculty 
                SET
                    firstname = :firstname,
                    lastname = :lastname,
                    contact = :contact,
                    email = :email,
                    username = :username,
                    password_hash = :password_hash,
                    program_id = :program_id"; // Include program_id
        $statement = $this->db->prepare($sql);
        $password_hash = $this->hashPassword($data['password']);
        $statement->execute([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'contact' => $data['contact'],
            'email' => $data['email'],
            'username' => $data['username'],
            'password_hash' => $password_hash,
            'program_id' => $data['program_id'] // Add program_id
        ]);
    
        $lastInsertId = $this->db->lastInsertId();
    
        return [
            'row_count' => $statement->rowCount(),
            'last_insert_id' => $lastInsertId
        ];
    }
    

    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function verifyAccess($email, $password)
    {
        $sql = "SELECT password_hash FROM faculty WHERE email = :email";
        $statement = $this->db->prepare($sql);
        $statement->execute([
            'email' => $email
        ]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (empty($result)) {
            return false;
        }

        return password_verify($password, $result['password_hash']);
    }

    public function getAllFaculty()
    {
        $sql = "
        SELECT 
            faculty.*, 
            programs.program_code 
        FROM 
            faculty
        LEFT JOIN 
            programs 
        ON 
            faculty.program_id = programs.id
    ";
    
    $statement = $this->db->prepare($sql);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $result;
    }

    public function getFacultyById($id)
{
    $sql = "SELECT * FROM faculty WHERE id = :id";
    $statement = $this->db->prepare($sql);
    $statement->execute(['id' => $id]);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

public function update($id, $data) {
    $sql = "UPDATE faculty 
            SET 
                lastname = :lastname,
                firstname = :firstname,
                contact = :contact,
                email = :email,
                username = :username,
                program_id = :program_id"; // Include program_id
    if (isset($data['password_hash'])) {
        $sql .= ", password_hash = :password_hash";
    }
    $sql .= " WHERE id = :id";

    $params = [
        'id' => $id,
        'lastname' => $data['lastname'],
        'firstname' => $data['firstname'],
        'contact' => $data['contact'],
        'email' => $data['email'],
        'username' => $data['username'],
        'program_id' => $data['program_id'], // Add program_id
    ];
    if (isset($data['password_hash'])) {
        $params['password_hash'] = $data['password_hash'];
    }

    $statement = $this->db->prepare($sql);
    return $statement->execute($params);
}


public function delete($id)
{
    $sql = "DELETE FROM faculty WHERE id = :id";
    $statement = $this->db->prepare($sql);
    return $statement->execute(['id' => $id]);
}

public function isEmailTaken($email, $excludeId = null) {
    $sql = "SELECT COUNT(*) FROM faculty WHERE email = :email";
    if ($excludeId) {
        $sql .= " AND id != :id";
    }

    $statement = $this->db->prepare($sql);
    $params = ['email' => $email];
    if ($excludeId) {
        $params['id'] = $excludeId;
    }

    $statement->execute($params);
    return $statement->fetchColumn() > 0;
}


}

