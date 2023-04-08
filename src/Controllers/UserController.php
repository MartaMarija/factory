<?php

namespace App\Controllers;

use App\AppError;
use App\DB\QueryBuilder;
use App\JsonResponse;
use App\Models\User;
use App\RequestInterface;
use App\Response;
use App\ResponseInterface;
use App\TwigResponse;

class UserController
{
    public function getUsers(RequestInterface $request): ResponseInterface
    {
        $qb = new QueryBuilder();
        try {
            $users = $qb
                ->select(['first_name', 'last_name'])
                ->from('user')
                ->where([
                    ['email' => ['LIKE', '%.com']],
                    ['email' => ['LIKE', 'm%']]
                ])
                ->limit(1)
                ->executeSelectAll();
        } catch (AppError $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
        }
        return new JsonResponse(['users' => $users]);
    }
    
    public function getUserById(RequestInterface $request): ResponseInterface
    {
        $id = $request->getParam('id');
        $qb = new QueryBuilder();
        try {
            $user = $qb
                ->select()
                ->from('user')
                ->where([
                    ['id' => ['=', $id]]
                ])
                ->executeSelectOne();
        } catch (AppError $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
        }
        return new JsonResponse(['user' => $user]);
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
    
    public function updateUserName(RequestInterface $request): ResponseInterface
    {
        $id = $request->getParam('id');
        $newName = $request->getParam('name');
        try {
            $qb = new QueryBuilder();
            $numOfUpdatedRows = $qb
                ->update('user')
                ->set(['first_name' => $newName])
                ->where([
                    ['id' => ['=', $id]]
                ])
                ->executeUpdate();
        } catch (AppError $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
        }
        return new JsonResponse(['numOfUpdatedRows' => $numOfUpdatedRows]);
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
