<?php

namespace App\Models;

use Core\Database\ActiveRecord\Model;

class Login extends Model
{
    protected static string $table = 'logins';
    protected static array $columns = ['id',  'user_id',  'email', 'password'];

    public ?int $id = null;
    public ?int $user_id = null;
    public ?int $admin_id = null;
    public string $email;
    public string $password;

    public function validates(): void
    {
        if (empty($this->email)) {
            throw new \InvalidArgumentException("O campo 'email' é obrigatório.");
        }

        if (empty($this->password)) {
            throw new \InvalidArgumentException("O campo 'password' é obrigatório.");
        }
    }
}
