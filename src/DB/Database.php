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
    private array $whereValues = [];
    
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
    
    public function select(array $columns = []): self
    {
        $this->columns = $this->connectColumns($columns);
        return $this;
    }
    
    public function from(string $table): self
    {
        $this->table = $table;
        return $this;
    }
    
    /**
     * connectWhereConditions sadrži detaljniji komentar
     */
    public function where(array $whereConditions): self
    {
        $result = $this->connectWhereConditions($whereConditions);
        $this->whereConditions = $result[0];
        $this->whereValues = $result[1];
        return $this;
    }
    
    public function execute(): array
    {
        $sqlStatement = $this->getSqlStatement();
        $statement = self::$connection->prepare($sqlStatement);
        foreach ($this->whereValues as $condition => $value) {
            $statement->bindValue($condition, $value);
        }
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function executeOne(): ?array
    {
        $sqlStatement = $this->getSqlStatement();
        $statement = self::$connection->prepare($sqlStatement);
        foreach ($this->whereValues as $condition => $value) {
            $statement->bindValue($condition, $value);
        }
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
    
    private function connectColumns(array $columns): string
    {
        if (empty($columns)) {
            return '*';
        }
        return implode(', ', $columns);
    }
    
    /**
     * @param array $whereConditions Dobiva uvjete u nekom od sljedećih oblika:
     * [['name' => 'ana'],
     * ['age' => ['>', 18]],
     * ['email' => ['LIKE', '%.com']]]
     *
     * INFO:
     * Svaki uvjet je u svojoj zagradi kako se ne bi prepisale vrijednosti.
     * U metodi postoji varijabla 'suffix' koja se dodaje onim uvjetima koji imaju isti naziv.
     * Nije moguće koristiti 'OR'
     *
     * @return array Na prvom indexu je where izjava, a na drugom vrijednosti koje će se binde-ati
     * ['`name`=:name AND `age` > :age AND `email` LIKE :email',
     * ['name'=>'ana', 'age'=>'18', 'email'=>'%.com']]
     *
     * Na kraju se trima AND jer će ostati jedan viška
     */
    public function connectWhereConditions(array $whereConditions): array
    {
        $whereStatement = '';
        $whereValues = [];
        $suffix = 1;
        foreach ($whereConditions as $condition) {
            foreach ($condition as $key => $value) {
                $parameterName = $key;
                if (isset($whereValues[$key])) {
                    $parameterName .= $suffix;
                    $suffix++;
                }
                if (is_array($value)) {
                    $operator = $value[0];
                    $whereValues[$parameterName] = $value[1];
                    $whereStatement .= "`$key` $operator :$parameterName AND";
                    continue;
                }
                $whereValues[$parameterName] = $value;
                $whereStatement .= "`$key`=:$parameterName AND";
            }
        }
        return [rtrim($whereStatement, 'AND'), $whereValues];
    }
    
    private function getSqlStatement(): string
    {
        $sqlStatement = "SELECT $this->columns FROM $this->table";
        if (!empty($this->whereConditions)) {
            $sqlStatement .= " WHERE $this->whereConditions";
        }
        return $sqlStatement;
    }
}
