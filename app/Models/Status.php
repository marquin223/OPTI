<?php

namespace App\Models;

use Core\Database\ActiveRecord\Model;

class Status extends Model
{
    protected static string $table = 'statuses';
    protected static array $columns = ['id', 'name'];

    public ?int $id = null;
    public string $name;

    public function validates(): void
    {
    }
}
