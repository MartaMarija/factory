<?php

namespace App\Controllers;

use App\AppError;
use App\DB\QueryBuilder;
use App\JsonResponse;
use App\RequestInterface;
use App\Response;
use App\ResponseInterface;
use App\TwigResponse;
use Twig\Error\Error;

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
                ->fetchAll();
        } catch (AppError $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
        }
        return new JsonResponse(['insertedUsers' => $users]);
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
                ->fetchOne();
        } catch (AppError $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
        }
        return new JsonResponse(['user' => $user]);
    }
    
    public function addUsers(RequestInterface $request): ResponseInterface
    {
        $users = $request->getParam('users');
        try {
            $qb = new QueryBuilder();
            $numberOfInserts = $qb
                ->insertInto('user', ['first_name', 'last_name', 'email', 'password'])
                ->values($users)
                ->save();
        } catch (AppError $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getCode());
        }
        return new JsonResponse(['numberOfInserts' => $numberOfInserts]);
    }
    
    public function getUsersTwig(RequestInterface $request): ResponseInterface
    {
        try {
            $qb = new QueryBuilder();
            $users = $qb
                ->select(['first_name'])
                ->from('user')
                ->fetchAll();
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
