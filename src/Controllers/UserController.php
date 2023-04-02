<?php

namespace App\Controllers;

use App\RequestInterface;
use App\Response;
use App\ResponseInterface;

class UserController
{
    private array $users = [
        ['name' => 'ana', 'age' => '20', 'favColor' => 'yellow'],
        ['name' => 'sara', 'age' => '30', 'favColor' => 'green']
    ];
    
    public function getUserAge(RequestInterface $request): ResponseInterface
    {
        $name = $request->getParamsValue('userName');
        foreach ($this->users as $user) {
            if ($user['name'] === $name) {
                $userAge = $user['age'];
                return new Response("User '$name' is $userAge years old!");
            }
        }
        return new Response("User '$name' doesn't exist!");
    }
}
