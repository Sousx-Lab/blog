<?php

use App\Db;
use App\Auth;
use App\Table\CategoryTable;

Auth::check();
$pdo = Db::getPDO();
$table = new CategoryTable($pdo);
$table->delete($params['id']);
header('Location: ' . $router->url('admin_categorys') . '?delete=1')

?>