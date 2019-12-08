<?php

use App\Db;
use App\Table\PostTable;

$pdo = Db::getPDO();

$table = new PostTable($pdo);
[$posts, $pagination] = $table->findPaginated();


$link = $router->url('home');
$title = " Yes";
?>
            
<h1>Le Blog</h1>
    <h3>Les articles</h3>
    <div class="row">
        <?php foreach($posts as $post): ?>
        <div class="col-md-3">
            <?php require 'card.php' ?>
        </div>
        <?php endforeach ?>
    </div>

    <div class="d-flex justify-content-between my-4">
        <?= $pagination->previousLink($link)?>
        <?= $pagination->nextLink($link)?>
    </div>

