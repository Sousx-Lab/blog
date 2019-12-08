<?php

use App\Db;
use App\Table\PostTable;
use App\HTML\Form;
use App\ObjectHelper;
use App\Table\CategoryTable;
use App\Validators\PostValidator;
use App\Auth;

Auth::check();

$title = "Edition d'article " . $params['id'];

$pdo = Db::getPDO();
$postTable = new PostTable($pdo);
$categoryTable = new CategoryTable($pdo);
$categories = $categoryTable->list();
$post = $postTable->find($params['id']);
$categoryTable->hydratePosts([$post]);

$success = false;
$errors = [];

if(!empty($_POST)){
    $v = new PostValidator($_POST, $postTable, $post->getId(), $categories);
    ObjectHelper::hydrate($post, $_POST,['name', 'slug', 'content', 'created_at']);
    if($v->validate()){
        $postTable->updatePost($post, $_POST['categories_ids']);
            $success = true;
    }else {
        $errors = $v->errors();
    }
}

$form = new Form($post, $errors);
?>
<?php if($success):?>
 <div class="alert alert-success">
     L'article à bien êté modifier
 </div>
 <?php endif;?>

 <?php if(!empty($errors)):?>
 <div class="alert alert-danger">
    L'article n'a pas pu être modifié!
 </div>
<?php endif ;?>

<h3> Editer l'article <?= e($post->getName())?></h3>
<?php require ('_form.php') ?>