<?php

namespace App\Models;

class User extends Model
{
    protected static string $table = 'user';
    public int $age;
    
    public function toArray(): array
    {
        parent::addExtraData('age', $this->age);
        return parent::toArray();
    }
}
