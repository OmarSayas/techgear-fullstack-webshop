<?php

namespace Repositories;

use PDO;
use PDOException;
use Repositories\Repository;

class UserRepository extends Repository
{
    function checkUsernamePassword($username, $password)
    {
        try {
            // retrieve the user with the given username
            $stmt = $this->connection->prepare("SELECT id, username, password, email, role FROM user WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\User');
            $user = $stmt->fetch();

            if (!$user)
                return false;

            // verify if the password matches the hash in the database
            $result = $this->verifyPassword($password, $user->password);

            if (!$result)
                return false;

            // do not pass the password hash to the caller
            $user->password = "";

            return $user;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    function updateRefreshToken($user)
    {
        $hashedToken = password_hash($user->refreshtoken, PASSWORD_DEFAULT);

        $stmt = $this->connection->prepare("UPDATE user SET refreshtoken = :refreshtoken WHERE username = :username");
        $stmt->bindParam(':username', $user->username);
        $stmt->bindParam(':refreshtoken', $hashedToken);
        $stmt->execute();
    }

    function checkRefreshToken($username, $refreshtoken)
    {
        // TODO: Check if the refresh token is valid. If so, return the user object.
        // If not, return false.
        $stmt = $this->connection->prepare("SELECT id, username, refreshtoken FROM user WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\User');
        $user = $stmt->fetch();
        if (!$user)
            return false;
        // verify if the refresh token matches the hash in the database
        $result = $this->verifyPassword($refreshtoken, $user->refreshtoken);
        if (!$result)
            return false;
        // do not pass the password hash to the caller
        $user->refreshtoken = "";
        return $user;
    }

    // hash the password (currently uses bcrypt)
    function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    // verify the password hash
    function verifyPassword($input, $hash)
    {
        return password_verify($input, $hash);
    }

    function signUp($user)
    {
        try {
            $hashedPassword = $this->hashPassword($user->password);

            $stmt = $this->connection->prepare("
            INSERT INTO user (username, password, email, role)
            VALUES (:username, :password, :email, :role)
        ");
            $stmt->bindParam(':username', $user->username);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':email', $user->email);
            $stmt->bindParam(':role', $user->role);

            $stmt->execute();
        } catch (PDOException $e) {
            echo $e;
            return false;
        }
    }
    function usernameExists($username)
    {
        $stmt = $this->connection->prepare("SELECT id FROM user WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\User');
        $user = $stmt->fetch();
        if (!$user)
            return false;
        return true;
    }

    public function getAll()
    {
        $stmt = $this->connection->prepare("SELECT id, username, email, role FROM user");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateRole($userId, $newRole)
    {
        $stmt = $this->connection->prepare("UPDATE user SET role = :role WHERE id = :id");
        $stmt->bindParam(':role', $newRole);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
    }
}
