<?php

namespace App\Models;

use App\Models\BaseModel;
use \PDO;

class BaseModel
{
    protected $db;

    public function __construct()
    {
        // Global Database Connection
        global $conn;
        $this->db = $conn;        
    }

    public function fill($payload)
    {
        foreach ($payload as $key => $value)
        {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

         // Fetch multiple rows from the database
         public function fetchAll($sql, $params = [])
         {
             $statement = $this->db->prepare($sql);
             $statement->execute($params);
             return $statement->fetchAll(PDO::FETCH_ASSOC);
         }
     
         // Fetch a single row from the database
         public function fetch($sql, $params = [])
         {
             $statement = $this->db->prepare($sql);
             $statement->execute($params);
             return $statement->fetch(PDO::FETCH_ASSOC);
         }
     
         // Execute a query that doesn't return rows (INSERT, UPDATE, DELETE)
         public function execute($sql, $params = [])
         {
             $statement = $this->db->prepare($sql);
             $statement->execute($params);
             return $statement->rowCount();
         }
}