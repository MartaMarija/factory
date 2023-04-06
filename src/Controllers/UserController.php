<?php

namespace App\Controllers;

use App\DB\Database;
use App\JsonResponse;
use App\RequestInterface;
use App\Response;
use App\ResponseInterface;
use App\TwigResponse;

class UserController
{
    public function getUsers(RequestInterface $request): ResponseInterface
    {
        $db = Database::getInstance();
        $users = $db
            ->select(['first_name', 'last_name'])
            ->from('user')
            ->where([
                ['email' => ['LIKE', '%.com']],
                ['email' => ['LIKE', 'm%']]
            ])
            ->fetchAll();
        return new JsonResponse(['insertedUsers' => $users]);
    }
    
    public function getUserById(RequestInterface $request): ResponseInterface
    {
        $id = $request->getParam('id');
        $db = Database::getInstance();
        $user = $db
            ->select()
            ->from('user')
            ->where([
                ['id' => $id]
            ])
            ->fetchOne();
        return new JsonResponse(['user' => $user]);
    }
    
    public function addUsers(RequestInterface $request): ResponseInterface
    {
        $users = $request->getParam('users');
        $db = Database::getInstance();
        $numberOfInserts = $db
            ->insertInto('user', ['first_name', 'last_name', 'email', 'password'])
            ->values($users)
            ->insert();
        return new JsonResponse(['numberOfInserts' => $numberOfInserts]);
    }
}


//    public function getUserAge(RequestInterface $request): ResponseInterface
//    {
//        $name = $request->getParam('userName');
//        foreach ($this->users1 as $user) {
//            if ($user['name'] === $name) {
//                $userAge = $user['age'];
//                return new TwigResponse('userAge', ['user' => $name, 'age' => $userAge]);
//            }
//        }
//        return new TwigResponse(
//            'error',
//            ['message' => "User '$name' doesn't exist!"],
//            Response::HTTP_NOT_FOUND
//        );
//    }
