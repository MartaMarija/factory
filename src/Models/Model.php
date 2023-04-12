<?php

namespace App\Models;

use App\DB\QueryBuilder;

abstract class Model
{
    protected static string $table;
    protected static string $primaryKeyName = 'id';
    protected array $data = [];
    protected array $extraData = [];
    
    public static function find(string $primaryKey): static
    {
        $data = (new QueryBuilder())
            ->select()
            ->from(static::$table)
            ->where([
                [static::$primaryKeyName => ['=', $primaryKey]]
            ])
            ->executeSelectOne();
        return self::hydrateNewInstance($data);
    }
    
    public static function getAll(): array
    {
        $rows = (new QueryBuilder())
            ->select()
            ->from(static::$table)
            ->executeSelectAll();
        return self::hydrateNewInstances($rows);
    }
    
    public static function toArrays(array $instances): array
    {
        return array_map(function ($instance): array {
            return $instance->toArray();
        }, $instances);
    }
    
    public static function delete(string $primaryKey): bool
    {
        return (new QueryBuilder())
            ->delete(static::$table)
            ->where([
                [static::$primaryKeyName => ['=', $primaryKey]]
            ])
            ->executeDelete();
    }
    
    public function save(): static
    {
        $id = (new QueryBuilder())
            ->insert(static::$table, $this->data)
            ->executeInsert();
        $this->{static::$primaryKeyName} = $id;
        return $this;
    }
    
    public function update(): static
    {
        $primaryKeyValue = $this->{static::$primaryKeyName};
        if (!isset($primaryKeyValue)) {
            return $this->save();
        }
        (new QueryBuilder())
            ->update(static::$table)
            ->set($this->data)
            ->where([
                [static::$primaryKeyName => ['=', $primaryKeyValue]]
            ])
            ->executeUpdate();
        return $this;
    }
    
    public function toArray(): array
    {
        return array_merge($this->data, $this->extraData);
    }
    
    public function __set($key, $value)
    {
        $this->data[$key] = $value;
    }
    
    public function __get($key)
    {
        return $this->data[$key] ?? null;
    }
    
    protected static function hydrateNewInstances(array $rows): array
    {
        $instances = [];
        foreach ($rows as $row) {
            $instances[] = static::hydrateNewInstance($row);
        }
        return $instances;
    }
    
    protected static function hydrateNewInstance(?array $data): static
    {
        $instance = new static();
        $instance->data = ($data == null) ? [] : $data;
        return $instance;
    }
    
    protected function addExtraData(string $key, mixed $value): void
    {
        $this->extraData[$key] = $value;
    }
}
