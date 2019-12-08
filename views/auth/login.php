<?php

use App\Db;
use App\Model\Users;
use App\HTML\Form;
use App\Table\Exception\NotFoundException;
use App\Table\UsersTable;

$user = new Users();
$errors = [];
if(!empty($_POST)){
    $user->setUsername($_POST['username']);
    $errors['password'] = 'identifiant ou mot de passe incorrect';

    if(!empty($_POST['username']) && !empty($_POST['password'])){
       $userTable = new UsersTable(Db::getPDO());
    try{
         $u = $userTable->findByUsername($_POST['username']);
         if($u !== false){
            if(password_verify($_POST['password'], $u->getPassword()) === true){
            session_start();
            $_SESSION['auth'] = $u->getid();
            header('Location: ' . $router->url('admin_posts'));
            exit();
            }
        }
        }catch (NotFoundException $e){
    }   
  }
}

$form = new Form($user, $errors);

?>
<div class="bg-light p-4 m-auto col-lg-6 text-center">
    <div class="container">
        <?php if(isset($_GET['forbidden'])):?>
        <div class="alert alert-danger">
            Vous ne pouvez pas accéder a cette page
        </div>
        <?php endif ?>
        <h1>Se Connecter</h1>
            <form action="<?= $router->url('login')?>" method="post">
                <?= $form->input('username', 'Nom d\'utilisateur');?>
                <?= $form->input('password', 'Votre mot de passe');?>
            <button type="submit" class="btn btn-primary ">Se Connecter</button>
        </form>
    </div>
</div>
