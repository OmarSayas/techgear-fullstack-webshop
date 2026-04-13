<?php
namespace Models;

class User {

    public int $id;
    public string $username;
    public string $password;
    public string $email;
    public string $refreshtoken;
    public int $role = 1; // 1 = user, 2 = admin

    public function getRoleLabel(): string {
        return match ($this->role) {
            2 => 'admin',
            1 => 'user',
            default => 'unknown'
        };
    }
}

?>