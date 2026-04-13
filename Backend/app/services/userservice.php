<?php

namespace Services;

use Repositories\UserRepository;

class UserService
{

    private $repository;

    function __construct()
    {
        $this->repository = new UserRepository();
    }

    public function checkUsernamePassword($username, $password)
    {
        return $this->repository->checkUsernamePassword($username, $password);
    }

    public function updateRefreshToken($user)
    {
        return $this->repository->updateRefreshToken($user);
    }

    public function checkRefreshToken($username, $refreshtoken)
    {
        return $this->repository->checkRefreshToken($username, $refreshtoken);
    }

    public function createUser($user)
    {
        return $this->repository->signUp($user);
    }

    public function usernameExists($username)
    {
        return $this->repository->usernameExists($username);
    }

    public function getAllUsers()
    {
        return $this->repository->getAll();
    }

    public function updateRole($userId, $newRole)
    {
        return $this->repository->updateRole($userId, $newRole);
    }

    public function validPassword($password)
    {
        if (strlen($password) < 8) {
            return false;
        }
        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }
        if (!preg_match('/[a-z]/', $password)) {
            return false;
        }
        if (!preg_match('/[0-9]/', $password)) {
            return false;
        }

        return true;
    }
}
