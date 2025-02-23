<?php

namespace App\Models;

use Core\Database\ActiveRecord\Model;

class Priority extends Model
{
    protected static string $table = 'priorities';
    protected static array $columns = ['id', 'name'];

    public ?int $id = null;
    public string $name;

    public static function exists($conditions): bool
    {
        return parent::exists($conditions);
    }

    public function validates(): void
    {
        if (empty($this->name)) {
            $this->addError('name', 'O campo nome é obrigatório.');
        }
    }
}
