<?php

use App\Db;
use App\Model\Post;
use App\HTML\Form;
use App\Table\PostTable;
use App\Validators\PostValidator;
use App\ObjectHelper;
use App\Table\CategoryTable;
use App\Auth;

Auth::check();
$title = "Créer un nouvel article";

$errors = [];
$post = new Post();
$pdo = Db::getPDO();
$categoryTable = new CategoryTable($pdo);
$categories = $categoryTable->list();

if(!empty($_POST)){ 
    $postTable = new PostTable($pdo);
    $v = new PostValidator($_POST, $postTable, $post->getId(), $categories);
    ObjectHelper::hydrate($post, $_POST,['name', 'slug', 'content', 'created_at']);
    if($v->validate()){
        $postTable->createPost($post, $_POST['categories_ids']);
        header('Location: ' . $router->url('admin_posts') . '?created=1');
        exit();
    }else {
        $errors = $v->errors();
    }
}

$form = new Form($post, $errors)
?>
 <?php if(!empty($errors)):?>
 <div class="alert alert-danger">
    L'article n'a pas pu être créer!
 </div>
<?php endif ;?>

<h3> Créer un nouvel article </h3>
<?php require ('_form.php')?>
