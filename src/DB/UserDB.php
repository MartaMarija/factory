<?php

namespace App\DB;

class UserDB
{
    public static function getUsers(): array
    {
        Database::getInstance();
        $sqlStatement = 'SELECT * FROM user';
        return Database::select($sqlStatement);
    }
}
