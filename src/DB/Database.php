<?php

namespace App\DB;

use PDO;
use PDOException;

class Database
{
    private static ?Database $instance = null;
    private static PDO $connection;
    private string $servername = 'localhost';
    private string $username = 'root';
    private string $password = 'pic';
    private string $database = 'factory_db';
    
    private function __construct()
    {
        try {
            self::$connection = new PDO(
                "mysql:host=$this->servername;dbname=$this->database",
                $this->username,
                $this->password
            );
        } catch (PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }
    }
    
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    public static function select(string $sqlStatement, array $data = []): array
    {
        $statement = self::$connection->prepare($sqlStatement);
        foreach ($data as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
