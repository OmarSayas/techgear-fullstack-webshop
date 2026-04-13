<?php

namespace Controllers;

use Services\UserService;
use Firebase\JWT\JWT;

class UserController extends Controller
{
    private $service;

    // initialize services
    function __construct()
    {
        $this->service = new UserService();
    }

    private function generateTokens($user)
    {
        $key = $this->getJwtSecret();

        $payload = [
            'iss' => 'http://example.org',
            'aud' => 'http://example.com',
            'iat' => time(),
            'nbf' => time(),
            'exp' => time() + 3600,
            'sub' => $user->username,
            'data' => [
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->getRoleLabel(),
                'userId' => $user->id
            ]
        ];

        $jwt = JWT::encode($payload, $key, 'HS256');
        return $jwt;
    }

    public function login()
    {

        // read user data from request body
        $loginData = $this->createObjectFromPostedJson("Models\\User");

        // get user from db
        $user = $this->service->checkUsernamePassword($loginData->username, $loginData->password);

        // if the method returned false, the username and/or password were incorrect
        if (!$user) {
            $this->respondWithError(401, "Incorrect username and/or password");
            return;
        }

        $jwt = $this->generateTokens($user);

        $refreshToken = bin2hex(random_bytes(32));
        $user->refreshtoken = $refreshToken;
        $this->service->updateRefreshToken($user);
        
        $this->respond([
            "token" => $jwt,
            "refreshToken" => $refreshToken
        ]);
    }

    public function refresh()
    {
        $refreshData = $this->createObjectFromPostedJson("Models\\User");
        $user = $this->service->checkRefreshToken($refreshData->username, $refreshData->refreshtoken);
        if (!$user) {
            $this->respondWithError(401, "Invalid refresh token");
            return;
        }
        $jwt = $this->generateTokens($user);
        $this->respond(["token" => $jwt]);
    }

    public function signUp()
    {
        $userData = $this->createObjectFromPostedJson("Models\\User");

        if ($this->service->usernameExists($userData->username)) {
            $this->respondWithError(409, "Username already exists");
            return;
        }

        if (!$this->service->validPassword($userData->password)) {
            $this->respondWithError(
                422,
                "Password must be at least 8 characters long and contain uppercase, lowercase, and numeric characters."
            );
            return;
        }

        $this->service->createUser($userData);

        $this->respond(["message" => "User successfully registered"]);
    }

    public function getAllUsers()
    {
        $this->requireRole('admin');
        $users = $this->service->getAllUsers();
        $this->respond($users);
    }

    public function updateUserRole($userId)
    {
        $this->requireRole('admin');
        $body = $this->createObjectFromPostedJson("Models\\User");

        $this->service->updateRole($userId, $body->role);
        $this->respond(["message" => "User role updated"]);
    }
}
