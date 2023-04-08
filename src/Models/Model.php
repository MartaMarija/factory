<?php

namespace App\Models;

use App\DB\QueryBuilder;

abstract class Model
{
    protected string $table;
    protected array $data;
    
    public function save(): void
    {
        $qb = new QueryBuilder();
        $id = $qb
            ->insert($this->getTable(), $this->getData())
            ->executeInsert();
        $this->id = $id;
    }
    
    public function update(): void
    {
    
    }
    
    public static function find(string $primaryKey): void
    {
    
    }
    
    public function toArray(): array
    {
        return [];
    }
    
    public function __set($key, $value)
    {
        $this->data[$key] = $value;
    }
    
    public function __get($key)
    {
        return $this->data[$key] ?? null;
    }
    
    protected function getTable(): string
    {
        return $this->table;
    }
    
    protected function getData(): array
    {
        return $this->data;
    }
}
