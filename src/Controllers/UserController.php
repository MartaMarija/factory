<?php

namespace App\Controllers;

use App\DB\QueryBuilder;
use App\Entities\User;
use App\Exceptions\AppError;
use App\JsonResponse;
use App\RequestInterface;
use App\ResponseInterface;
use App\TwigResponse;

class UserController
{
    public function getUsers(RequestInterface $request): ResponseInterface
    {
        try {
            $users = User::getAll();
        } catch (AppError $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
        }
        return new JsonResponse(['users' => $users]);
    }
    
    public function getUserById(RequestInterface $request): ResponseInterface
    {
        $userId = $request->getParam('id');
        try {
            $user = User::find($userId);
        } catch (AppError $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
        }
        return new JsonResponse(['user' => $user->toArray()]);
    }
    
    public function addUser(RequestInterface $request): ResponseInterface
    {
        $userData = $request->getParam('user');
        $user = new User();
        $user->first_name = $userData['first_name'];
        $user->last_name = $userData['last_name'];
        $user->email = $userData['email'];
        $user->password = $userData['password'];
        $user->user_type_id = $userData['user_type_id'];
        try {
            $user->save();
        } catch (AppError $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
        }
        return new JsonResponse(['id' => $user->id]);
    }
    
    public function updateUserEmail(RequestInterface $request): ResponseInterface
    {
        $id = $request->getParam('id');
        $newEmail = $request->getParam('email');
        try {
            $user = User::find($id);
            $user->email = $newEmail;
            $user = $user->update();
        } catch (AppError $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
        }
        return new JsonResponse(['user' => $user->toArray()]);
    }
    
    public function getUsersTwig(RequestInterface $request): ResponseInterface
    {
        try {
            $qb = new QueryBuilder();
            $users = $qb
                ->select(['first_name'])
                ->from('user')
                ->executeSelectAll();
        } catch (AppError $e) {
            return new TwigResponse(
                'error',
                ['error' => $e->getMessage()],
                $e->getCode()
            );
        }
        return new TwigResponse('users', ['users' => $users]);
    }
}
