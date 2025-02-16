<?php

namespace App\Models;

use Core\Database\ActiveRecord\Model;
use Core\Database\ActiveRecord\BelongsTo;

class Ticket extends Model
{
    protected static string $table = 'tickets';
    protected static array $columns = [
     'id',
     'title',
     'description',
     'admin_id',
     'user_id',
     'status_id',
     'priority_id',
     'created_date',
     'closing_date'
    ];

    public ?int $id = null;
    public string $title;
    public string $description;
    public ?int $admin_id = null;
    public ?int $user_id = null;
    public ?int $status_id = null;
    public ?int $priority_id;
    public ?string $created_date = null;
    public ?string $closing_date = null;

    /**
     * @return void
     */
    public function validates(): void
    {
        if (empty($this->title)) {
            $this->addError('title', 'O campo título é obrigatório.');
        }

        if (empty($this->description)) {
            $this->addError('description', 'O campo descrição é obrigatório.');
        }

        if (empty($this->admin_id) && empty($this->user_id)) {
            $this->addError('admin_id', 'O ticket deve estar vinculado a um admin ou a um usuário.');
        }
    }

    public function admin(): ?Admin
    {
        return Admin::findById($this->admin_id);
    }

    public function user(): ?User
    {
        return User::findById($this->user_id);
    }

    public function status(): ?Status
    {
        return Status::findById($this->status_id);
    }

    public function beforeSave(): void
    {
        if ($this->id === null) {
            $this->created_date = date('Y-m-d H:i:s');
        }

        if ($this->priority_id === null) {
            $this->priority_id = 1;
        }

        $this->closing_date = date('Y-m-d H:i:s');
    }

    public function priority(): BelongsTo
    {
        return new BelongsTo($this, Priority::class, 'priority_id');
    }
}
