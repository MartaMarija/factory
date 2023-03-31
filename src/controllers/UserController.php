<?php

namespace App\controllers;

class UserController
{
    private array $users=[
        ['name'=>'ana', 'age'=>'20', 'favColor'=>'yellow'],
        ['name'=>'sara', 'age'=>'30', 'favColor'=>'green']
    ];
    public function getUserByName(string $name): ?array
    {
        foreach ($this->users as $user) {
            if ($user['name'] === $name) {
                return $user;
            }
        }
        return null;
    }
}
