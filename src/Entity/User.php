<?php

namespace Entity;

class User
{
    private int $id;
    private string $login;
    private string $password;
    private Role $role;
    private string $username;
    private string $email;
    private bool $isAvailable;

    public function __construct(int $id, string $login, string $password, Role $role, string $username, string $email, bool $isAvailable) {
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
        $this->role = $role;
        $this->username = $username;
        $this->email = $email;
        $this->isAvailable = $isAvailable;
    }

    public function getId() : int {
        return $this->id;
    }

    public function getLogin() : string {
        return $this->login;
    }

    public function getPassword() : string {
        return $this->password;
    }

    public function getRole() : Role {
        return $this->role;
    }

    public function getUsername() : string {
        return $this->username;
    }

    public function getEmail() : string {
        return $this->email;
    }

    public function getIsAvailable() : bool {
        return $this->isAvailable;
    }

}
