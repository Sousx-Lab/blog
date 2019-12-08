<?php

use App\Db;
use App\Table\CategoryTable;
use App\HTML\Form;
use App\ObjectHelper;
use App\Validators\CategoryValidator;
use App\Auth;

Auth::check();
$title = "Edition d'article " . $params['id'];

$pdo = Db::getPDO();
$categoryTable = new CategoryTable($pdo);
$categorie = $categoryTable->find($params['id']);
$success = false;
$errors = [];

if(!empty($_POST)){
    $v = new CategoryValidator($_POST, $categoryTable, $categorie->getId());
    ObjectHelper::hydrate($categorie, $_POST,['name', 'slug']);
    if($v->validate()){
        $categoryTable->updateCategory($categorie);
        $success = true;
    }else {
        $errors = $v->errors();
    }
}
$form = new Form($categorie, $errors);
?>
<?php if($success):?>
 <div class="alert alert-success">
     La catégorie à bien êté modifier
 </div>
 <?php endif;?>

 <?php if(!empty($errors)):?>
 <div class="alert alert-danger">
    La catégorie n'a pas pu être modifié!
 </div>
<?php endif ;?>

<h3> Editer la catégorie <?= e($categorie->getName())?></h3>
<?php require ('_form.php') ?>