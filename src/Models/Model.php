<?php

namespace App\Models;

use App\DB\QueryBuilder;

abstract class Model
{
    protected static string $table;
    protected static string $primaryKeyName = 'id';
    protected array $data;
    protected array $extraData;
    
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
