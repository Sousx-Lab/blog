<?php

use App\Db;
use App\HTML\Form;
use App\Model\Category;
use App\Validators\CategoryValidator;
use App\ObjectHelper;
use App\Table\CategoryTable;
use App\Auth;

Auth::check();
$title = "Créer une nouvelle categorie";
$errors = [];
$categorie = new Category();


if(!empty($_POST)){ 
    $pdo = Db::getPDO();
    $categoryTable = new CategoryTable($pdo);
    
    $v = new CategoryValidator($_POST, $categoryTable, $categorie->getId());
    ObjectHelper::hydrate($categorie, $_POST,['name', 'slug']);
    if($v->validate()){
        $categoryTable->createCategory($categorie);
        header('Location: ' . $router->url('admin_categorys') . '?created=1');
        exit();
    }else {
        $errors = $v->errors();
    }
}

$form = new Form($categorie, $errors)
?>
 <?php if(!empty($errors)):?>
 <div class="alert alert-danger">
    La catégorie n'a pas pu être créer!
 </div>
<?php endif ;?>

<h3> Créer une nouvelle catégorie </h3>
<?php require ('_form.php')?>