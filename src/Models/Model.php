<?php

namespace App\Models;

use App\AppError;
use App\DB\QueryBuilder;
use App\Response;
use Error;

abstract class Model
{
    protected static string $table;
    protected static string $primaryKeyName = 'id';
    protected array $data;
    
    public function save(): void
    {
        $id = (new QueryBuilder())
            ->insert(static::$table, $this->data)
            ->executeInsert();
        $this->{static::$primaryKeyName} = $id;
    }
    
    public function update(): int
    {
        $primaryKeyValue = $this->{static::$primaryKeyName};
        if (!isset($primaryKeyValue)) {
            throw new AppError(Response::HTTP_NOT_FOUND, 'ID not found!');
        }
        return (new QueryBuilder())
            ->update(static::$table)
            ->set($this->data)
            ->where([
                [static::$primaryKeyName => ['=', $primaryKeyValue]]
            ])
            ->executeUpdate();
    }
    
    public static function find(string $primaryKey): static
    {
        $data = (new QueryBuilder())
            ->select()
            ->from(static::$table)
            ->where([
                [static::$primaryKeyName => ['=', $primaryKey]]
            ])
            ->executeSelectOne();
        
        $instance = new static();
        $instance->data = $data;
        
        return $instance;
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
}
