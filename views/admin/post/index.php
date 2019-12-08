<?php

use App\Auth;
use App\Db;
use App\Table\PostTable;
use App\URL;

Auth::check();
$title = "Liste d'articles";

$pdo = Db::getPDO();

[$posts, $pagination] = (new PostTable($pdo))->findPaginated();
$links = $router->url('admin_posts');
?>
<?php if (isset($_GET['created'])) : ?>
    <div class="alert alert-success">
        L'article a bien été crée
    </div>
<?php endif; ?>
<?php if (isset($_GET['delete'])) : ?>
    <div class="alert alert-success">
        L'article a bien été supprimer
    </div>
<?php endif; ?>
<div class="nav-item m-1">
    <a href="<?= $router->url('new_post') ?>" data-toggle="tooltip" title="Créer un article"><small> Créer un article </small><i class="fas fa-pen"></i></a>
</div>
<table class="table table-striped">
    <thead>
        <th>Action</th>
        <th><?= URL::getSortDir($links, 'name', 'Titre') ?></th>
        <th><?= URL::getSortDir($links, 'created_at', 'Date de création') ?></th>
        <th><?= URL::getSortDir($links, 'id', 'ID') ?></th>
    </thead>
    <tbody>
        <?php foreach ($posts as $post) : ?>
            <tr>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <small></small> <i class="fas fa-bars"></i></a>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                            <form action="<?= $router->url('edit_post', ['id' => $post->getId()]) ?>" method="POST" style="display:inline">
                                <button type="submit" class="dropdown-item"> Editer <i class="fas fa-edit"></i></span></button>
                            </form>
                            <form action="<?= $router->url('delete_post', ['id' => $post->getId()]) ?>" method="POST" onsubmit="return confirm('Voulez vous vraiment supprimer cette article ?') ">
                                <button type="submit" class="dropdown-item text-danger"><small> Supprimer </small><i class="fas fa-trash"></i></button>
                            </form>
                         </td>
                    <td>
                    <a href="<?= $router->url('edit_post', ['id' => $post->getId()]) ?>" data-toggle="tooltip" title="Modifier l'article">
                        <?= e($post->getName()) ?>
                    </a>
                <td>
                    <?= $post->getDateFr() ?>
                    </td>
                <td>
                    <?= $post->getId() ?>
                </td>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="d-flex justify-content-between my-4">
    <?= $pagination->previousLink($links) ?>
    <?= $pagination->nextLink($links) ?>
</div>