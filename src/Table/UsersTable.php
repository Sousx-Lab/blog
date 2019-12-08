<?php

namespace App\Table;

use App\Model\Users;
use App\Table\Exception\NotFoundException;
use PDO;

class UsersTable extends Table {

    public $table = 'users';
    public $class = Users::class;

    public function findByUsername(string $username)
    {
        $query = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE username = :username");
        $query->execute(['username' => $username]);
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        $result = $query->fetch();
        return $result;
    }

}