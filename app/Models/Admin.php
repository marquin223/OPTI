<?php

namespace App\Models;

use Core\Database\ActiveRecord\Model;
use App\Models\Login;

class Admin extends Model
{
    protected static string $table = 'admins';
    protected static array $columns = ['id', 'name', 'date_birth', 'phone'];

    public ?int $id = null;
    public string $name;
    public ?string $date_birth = null;
    public ?string $phone = null;

    /**
     * @return Login|null
     */
    public function login()
    {
        return Login::first(['admin_id' => $this->id]);
    }


    /**
     * @return void
     */
    public function validates(): void
    {
        if (empty($this->name)) {
            $this->addError('name', 'O campo nome é obrigatório.');
        }

        if (!empty($this->date_birth) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $this->date_birth)) {
            $this->addError('date_birth', 'O campo data de nascimento deve estar no formato YYYY-MM-DD.');
        }
    }

    public function getTickets() {
      return Ticket::findByAdminId($this->id); // Implementação da relação N x 1
    }
}
