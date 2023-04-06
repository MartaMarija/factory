<?php

namespace App\DB;

use App\AppError;
use App\Response;
use PDO;
use PDOException;
use PDOStatement;

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
    
    public static function getInstance(): ?Database
    {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    public function fetchAll(string $sqlStatement, array $placeholdersValues): array
    {
        try {
            $statement = $this->executeSqlStatement($sqlStatement, $placeholdersValues);
        } catch (PDOException $e) {
            throw new AppError(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function fetchOne(string $sqlStatement, array $placeholdersValues): ?array
    {
        try {
            $statement = $this->executeSqlStatement($sqlStatement, $placeholdersValues);
        } catch (PDOException $e) {
            throw new AppError(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
    
    public function insert(string $sqlStatement, array $placeholdersValues): string
    {
        try {
            $statement = self::$connection->prepare($sqlStatement);
            $this->bindInsertValues($placeholdersValues, $statement);
            $statement->execute();
        } catch (PDOException $e) {
            throw new AppError(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
        return $statement->rowCount();
    }
    
    public function update(string $sqlStatement, array $placeholdersValues): string
    {
        try {
            $statement = $this->executeSqlStatement($sqlStatement, $placeholdersValues);
        } catch (PDOException $e) {
            throw new AppError(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
        return $statement->rowCount();
    }
    
    private function bindInsertValues(array $placeholdersValues, PDOStatement $statement): void
    {
        $numberOfPlaceholders = 1;
        foreach ($placeholdersValues as $row) {
            foreach ($row as $value) {
                $statement->bindValue($numberOfPlaceholders, $value);
                $numberOfPlaceholders++;
            }
        }
    }
    
    public function executeSqlStatement(string $sqlStatement, array $placeholdersValues): PDOStatement|false
    {
        $statement = self::$connection->prepare($sqlStatement);
        foreach ($placeholdersValues as $condition => $value) {
            $statement->bindValue($condition, $value);
        }
        $statement->execute();
        return $statement;
    }
}
