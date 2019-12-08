<?php

use App\Auth;
use App\Db;
use App\Table\CategoryTable;
use App\URL;

Auth::check();
$title = 'Liste des catégories';

$pdo = Db::getPDO();

[$categories, $pagination] = (new CategoryTable($pdo))->findPaginatedCategory();
$links = $router->url('admin_categorys');
?>
<?php if (isset($_GET['created'])) : ?>
    <div class="alert alert-success">
        La categorie a bien été crée
    </div>
<?php endif; ?>

<?php if (isset($_GET['delete'])) : ?>
    <div class="alert alert-success">
        La categorie a bien été supprimer
    </div>
<?php endif; ?>
        <div class="nav-item m-1">
            <a href="<?= $router->url('new_category') ?>" data-toggle="tooltip" title="Créer une catégorie"><small> Créer une catégorie </small><i class="fas fa-pen"></i></a>
        </div>
<table class="table">
    <thead>
        <th>#Action</th>
        <th><?= URL::getSortDir($links, 'name', 'Titre') ?></th>
        <th><?= URL::getSortDir($links, 'id', 'ID' )?></th>
    </thead>
    <tbody>
        <?php foreach ($categories as $categorie) : ?>
            <tr>
                 <td>
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <small></small> <i class="fas fa-bars"></i></a>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                            <form action="<?= $router->url('edit_category', ['id' => $categorie->getId()]) ?>" method="POST" style="display:inline">
                                <button type="submit" class="dropdown-item"> Editer <i class="fas fa-edit"></i></span></button>
                            </form>

                            <form action="<?= $router->url('delete_category', ['id' => $categorie->getId()]) ?>" method="POST" onsubmit="return confirm('Voulez vous vraiment supprimer cette categorie ?') ">
                                <button type="submit" class="dropdown-item text-danger"><small> Supprimer </small><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </td>
                <td>
                    <a href="<?= $router->url('edit_category', ['id' => $categorie->getId()]) ?>" data-toggle="tooltip" title="Modifier la catégorie">
                        <?= e($categorie->getName()) ?>
                    </a>
                </td>
                <td>
                    <?= $categorie->getId() ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="d-flex justify-content-between my-4">
    <?= $pagination->previousLink($links) ?>
    <?= $pagination->nextLink($links) ?>
</div>