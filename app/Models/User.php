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
     * @return void
     */
    public function validates(): void
    {
        if (empty($this->name)) {
            $this->addError('name', 'O campo nome é obrigatório.');
        }

        if (!empty($this->phone) && !preg_match('/^\+?[0-9]{10,15}$/', $this->phone)) {
            $this->addError('phone', 'O campo telefone é inválido.');
        }
    }

    public function getTickets() {
      return Ticket::findByUserId($this->id); // Implementação da relação N x 1
    }
}
