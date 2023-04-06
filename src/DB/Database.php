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
    private string $table;
    private string $columns;
    private string $whereConditions = '';
    private array $placeholdersValues = [];
    private string $insertPlaceholders = '';
    
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
    
    public function select(array $columns = []): self
    {
        $this->columns = $this->connectQueryColumns($columns);
        return $this;
    }
    
    public function from(string $table): self
    {
        $this->table = $table;
        return $this;
    }
    
    public function where(array $whereConditions): self
    {
        $this->whereConditions = $this->connectWhereConditions($whereConditions);
        return $this;
    }
    
    private function connectQueryColumns(array $columns): string
    {
        if (empty($columns)) {
            return '*';
        }
        return implode(', ', $columns);
    }
    
    /**
     * @param array $whereConditions Dobiva uvjete u nekom od sljedećih oblika:
     * [['name' => ['=', 'ana']],
     * ['age' => ['>', 18]],
     * ['email' => ['LIKE', '%.com']]]
     *
     * INFO:
     * Svaki uvjet je u svojoj zagradi kako se ne bi prepisale vrijednosti.
     * U metodi postoji varijabla 'suffix' koja se dodaje onim uvjetima koji imaju isti naziv.
     * Nije moguće koristiti 'OR'
     *
     * @return string where izjava
     * '`name`=:name AND `age` > :age AND `email` LIKE :email'
     *
     * Na kraju se trima AND jer će ostati jedan viška
     */
    public function connectWhereConditions(array $whereConditions): string
    {
        $whereStatement = '';
        $suffix = 1;
        foreach ($whereConditions as $condition) {
            foreach ($condition as $key => $value) {
                $parameterName = $key;
                if (isset($this->placeholdersValues[$key])) {
                    $parameterName .= $suffix;
                    $suffix++;
                }
                $operator = $value[0];
                $this->placeholdersValues[$parameterName] = $value[1];
                $whereStatement .= "`$key` $operator :$parameterName AND";
            }
        }
        return rtrim($whereStatement, 'AND');
    }
    
    private function getQueryStatement(): string
    {
        $sqlStatement = "SELECT $this->columns FROM $this->table";
        if (!empty($this->whereConditions)) {
            $sqlStatement .= " WHERE $this->whereConditions";
        }
        return $sqlStatement;
    }
    
    public function fetchAll(): array
    {
        $sqlStatement = $this->getQueryStatement();
        try {
            $statement = self::$connection->prepare($sqlStatement);
            foreach ($this->placeholdersValues as $condition => $value) {
                $statement->bindValue($condition, $value);
            }
            $statement->execute();
        } catch (PDOException $e) {
            //            return $e->getMessage();
        }
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function fetchOne(): ?array
    {
        $sqlStatement = $this->getQueryStatement();
        try {
            $statement = self::$connection->prepare($sqlStatement);
            foreach ($this->placeholdersValues as $condition => $value) {
                $statement->bindValue($condition, $value);
            }
            $statement->execute();
        } catch (PDOException $e) {
            //return $e->getMessage();
        }
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
    
    public function insertInto(string $table, array $columns = []): self
    {
        $this->table = $table;
        $this->columns = $this->connectInsertColumns($columns);
        return $this;
    }
    
    public function values(array $rows): self
    {
        $this->placeholdersValues = $rows;
        $numberOfColumns = count($rows[0]);
        $numberOfRows = count($rows);
        $this->insertPlaceholders = $this->connectInsertPlaceholders($numberOfColumns, $numberOfRows);
        return $this;
    }
    
    private function connectInsertColumns(array $columns): string
    {
        if (empty($columns)) {
            return '';
        }
        return implode(', ', $columns);
    }
    
    private function connectInsertPlaceholders(int $numberOfColumns, int $numberOfRows): string
    {
        $rowPlaceholders = '(' . implode(',', array_fill(0, $numberOfColumns, '?')) . ')';
        return implode(',', array_fill(0, $numberOfRows, $rowPlaceholders));
    }
    
    private function getInsertStatement(): string
    {
        $sqlStatement = "INSERT INTO $this->table";
        if (!empty($this->columns)) {
            $sqlStatement .= "( $this->columns )";
        }
        $sqlStatement .= " VALUES $this->insertPlaceholders";
        return $sqlStatement;
    }
    
    public function insert(): string
    {
        $sqlStatement = $this->getInsertStatement();
        try {
            $statement = self::$connection->prepare($sqlStatement);
            $numberOfPlaceholders = 1;
            foreach ($this->placeholdersValues as $row) {
                foreach ($row as $value) {
                    $statement->bindValue($numberOfPlaceholders, $value);
                    $numberOfPlaceholders++;
                }
            }
            $statement->execute();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
        return $statement->rowCount();
    }
}
