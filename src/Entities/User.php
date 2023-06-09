<?php

namespace App\Entities;

use App\DB\QueryBuilder;
use App\Models\HasTimestamps;
use App\Models\Model;

class User extends Model
{
    use HasTimestamps;
    
    protected static string $table = 'user';
    public int $age;
    
    public function toArray(): array
    {
        parent::addExtraData('age', $this->age ?? null);
        return parent::toArray();
    }
}
