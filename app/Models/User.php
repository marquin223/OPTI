<?php

namespace App\Models;

use Core\Database\ActiveRecord\Model;

class User extends Model
{
    protected static string $table = 'users';
    protected static array $columns = ['id', 'name', 'phone'];

    public ?int $id = null;
    public string $name;
    public string $phone;

    /**
     * Valida os dados antes de salvar ou atualizar o registro.
     *
     * @return void
     */
    public function validates(): void
    {
        // Validações básicas
        if (empty($this->name)) {  // Acessando usando __get
            $this->addError('name', 'O campo nome é obrigatório.');
        }

        if (!empty($this->phone) && !preg_match('/^\+?[0-9]{10,15}$/', $this->phone)) {
            $this->addError('phone', 'O campo telefone é inválido.');
        }
    }
}
