<?php

namespace App\DB;

class UserDB
{
    public static function getUsers(): array
    {
        $sqlStatement = 'SELECT * FROM user';
        return Database::select($sqlStatement);
    }
    
    public static function getUserById(string $id): ?array
    {
        $sqlStatement = 'SELECT * FROM user WHERE `id`=:id';
        return Database::selectOne($sqlStatement, ['id' => $id]);
    }
}
