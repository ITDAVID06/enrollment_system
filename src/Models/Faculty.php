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
                    firstname=:firstname,
                    lastname=:lastname,
                    contact=:contact,
                    email=:email,
                    username=:username,
                    password_hash=:password_hash";        
        $statement = $this->db->prepare($sql);
        $password_hash = $this->hashPassword($data['password']);
        $statement->execute([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'contact' => $data['contact'],
            'email' => $data['email'],
            'username' => $data['username'],
            'password_hash' => $password_hash
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
        $sql = "SELECT * FROM faculty";
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

public function update($id, $data)
{
       // Base SQL query
       $sql = "UPDATE faculty 
       SET lastname = :lastname,
           firstname = :firstname,
           contact = :contact,
           email = :email,
           username = :username";

// Include password only if provided
if (isset($data['password_hash'])) {
   $sql .= ", password_hash = :password_hash";
}

$sql .= " WHERE id = :id";

// Prepare parameters
$params = [
   'id' => $id,
   'lastname' => $data['lastname'] ?? null,
   'firstname' => $data['firstname'] ?? null,
   'contact' => $data['contact'] ?? null,
   'email' => $data['email'] ?? null,
   'username' => $data['username'] ?? null,
];

// Add password_hash if provided
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

}

