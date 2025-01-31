<?php

namespace App\Models;

use Core\Database\ActiveRecord\Model;

class Ticket extends Model
{
    protected static string $table = 'tickets';
    protected static array $columns = [
        'id', 'user_id', 'admin_id', 'status_id', 'priority_id', 'title', 'description', 'created_date', 'closing_date'
    ];

    public ?int $id = null;
    public int $user_id;
    public ?int $admin_id = null;
    public int $status_id;
    public int $priority_id;
    public string $title;
    public ?string $description = null;
    public string $created_date;
    public ?string $closing_date = null;

    public function user()
    {
        return User::find($this->user_id);
    }

    public function admin()
    {
        return $this->admin_id ? Admin::find($this->admin_id) : null;
    }

    public function status()
    {
        return Status::find($this->status_id);
    }

    public function priority()
    {
        return Priority::find($this->priority_id);
    }

    public function validates(): void
    {
        if (empty($this->title)) {
            $this->addError('title', 'O título do ticket é obrigatório.');
        }
        if (empty($this->user_id)) {
            $this->addError('user_id', 'Usuário é obrigatório.');
        }
        if (empty($this->status_id)) {
            $this->addError('status_id', 'Status é obrigatório.');
        }
        if (empty($this->priority_id)) {
            $this->addError('priority_id', 'Prioridade é obrigatória.');
        }
    }

    public function markInProgress()
    {
        $this->status_id = Status::IN_PROGRESS;
        $this->save();
    }

    public function markResolved()
    {
        $this->status_id = Status::RESOLVED;
        $this->closing_date = date('Y-m-d');
        $this->save();
    }

    public function reopen()
    {
        $this->status_id = Status::OPEN;
        $this->closing_date = null;
        $this->save();
    }
}
