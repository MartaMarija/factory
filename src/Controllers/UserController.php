<?php

namespace App\Controllers;

use App\DB\UserDB;
use App\JsonResponse;
use App\RequestInterface;
use App\Response;
use App\ResponseInterface;
use App\TwigResponse;

class UserController
{
    public function getUsers(RequestInterface $request): ResponseInterface
    {
        $users = UserDB::getUsers();
        return new JsonResponse(['users' => $users]);
    }
    
    public function getUserById(RequestInterface $request): ResponseInterface
    {
        $id = $request->getParam('id');
        $user = UserDB::getUserById($id);
        return new JsonResponse(['user' => $user]);
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
