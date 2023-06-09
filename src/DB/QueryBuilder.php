<?php

namespace App\DB;

use App\Exceptions\AppError;
use App\Response;
use App\Utils;

class QueryBuilder
{
    private string $table;
    private string $columns;
    private string $whereConditions = '';
    private string $limit = '';
    private array $placeholdersValues = [];
    private string $insertPlaceholders = '';
    private string $setClause = '';
    
    //-------------- SELECT --------------//
    
    public function executeSelectAll(): array
    {
        $sqlStatement = $this->getQueryStatement();
        $db = Database::getInstance();
        return $db->fetchAll($sqlStatement, $this->placeholdersValues);
    }
    
    public function executeSelectOne(): ?array
    {
        $sqlStatement = $this->getQueryStatement();
        $db = Database::getInstance();
        return $db->fetchOne($sqlStatement, $this->placeholdersValues);
    }
    
    private function getQueryStatement(): string
    {
        $sqlStatement = "SELECT $this->columns FROM $this->table";
        if (!empty($this->whereConditions)) {
            $sqlStatement .= " WHERE $this->whereConditions";
        }
        if (!empty($this->limit)) {
            $sqlStatement .= " LIMIT $this->limit";
        }
        return $sqlStatement;
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
    
    public function limit(int $numOfRows): self
    {
        $this->limit = $numOfRows;
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
    private function connectWhereConditions(array $whereConditions): string
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
    
    //-------------- INSERT --------------//
    
    public function executeInsert(): string
    {
        $sqlStatement = $this->getInsertStatement();
        $db = Database::getInstance();
        return $db->insert($sqlStatement, $this->placeholdersValues);
    }
    
    private function getInsertStatement(): string
    {
        return "INSERT INTO $this->table ($this->columns) VALUES $this->insertPlaceholders";
    }
    
    public function insert(string $table, array $rows): self
    {
        if ($this->isOneRow($rows)) {
            $rows = [$rows];
        }
        if (!Utils::isAssocArray($rows[0])) {
            throw new AppError(Response::HTTP_BAD_REQUEST, 'Bad format!');
        }
        $this->table = $table;
        $this->handleRows($rows);
        $this->columns = $this->connectInsertColumns($rows);
        return $this;
    }
    
    private function isOneRow(array $rows): bool
    {
        return !is_array($rows[0]);
    }
    
    public function handleRows(array $rows): void
    {
        $this->placeholdersValues = $rows;
        $numberOfColumns = count($rows[0]);
        $numberOfRows = count($rows);
        $this->insertPlaceholders = $this->connectInsertPlaceholders($numberOfColumns, $numberOfRows);
    }
    
    private function connectInsertPlaceholders(int $numberOfColumns, int $numberOfRows): string
    {
        $rowPlaceholders = '(' . implode(',', array_fill(0, $numberOfColumns, '?')) . ')';
        return implode(',', array_fill(0, $numberOfRows, $rowPlaceholders));
    }
    
    private function connectInsertColumns(array $rows): string
    {
        $columns = array_keys($rows[0]);
        return implode(', ', $columns);
    }
    
    //-------------- UPDATE --------------//
    
    public function executeUpdate(): int
    {
        $sqlStatement = $this->getUpdateStatement();
        $db = Database::getInstance();
        return $db->update($sqlStatement, $this->placeholdersValues);
    }
    
    private function getUpdateStatement(): string
    {
        $sqlStatement = "UPDATE $this->table SET $this->setClause";
        if (!empty($this->whereConditions)) {
            $sqlStatement .= " WHERE $this->whereConditions";
        }
        return $sqlStatement;
    }
    
    public function update(string $table): self
    {
        $this->table = $table;
        return $this;
    }
    
    public function set(array $data): self
    {
        $this->setClause = $this->connectSetClause($data);
        $this->placeholdersValues = $data;
        return $this;
    }
    
    private function connectSetClause(array $data): string
    {
        return implode(', ', array_map(fn ($key): string => "`$key`=:$key", array_keys($data)));
    }
    
    //-------------- DELETE --------------//
    
    public function executeDelete(): bool
    {
        $sqlStatement = $this->getDeleteStatement();
        $db = Database::getInstance();
        return $db->delete($sqlStatement, $this->placeholdersValues);
    }
    
    private function getDeleteStatement(): string
    {
        $sqlStatement = "DELETE FROM $this->table";
        if (!empty($this->whereConditions)) {
            $sqlStatement .= " WHERE $this->whereConditions";
        }
        return $sqlStatement;
    }
    
    public function delete(string $table): self
    {
        $this->table = $table;
        return $this;
    }
}
