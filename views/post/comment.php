<?php

use App\Table\CommentTable;
use App\HTML\Form;
use App\Model\Comment;
use App\Validators\CommentValidator;
use App\ObjectHelper;

$errors = [];
$comment = new Comment();
$commentTable = new CommentTable($pdo);


if (!empty($_POST)) {
    $v = new CommentValidator($_POST, $commentTable, $comment->getId());
    ObjectHelper::hydrate($comment, $_POST, ['user', 'email', 'comment']);
    if ($v->validate()) {
        $commentTable->createComment($comment, $id);
        header('Location: ' . $url . '?comment=1');
    } else {
        $errors = $v->errors();
    }
}
[$comments, $count] = $commentTable->findCommentPost($id);

$form = new Form($comment, $errors);

?>

<div class="mb-4 mt-5">
<div class="bg-light py-4 col-lg-9">
        <div class="container">
            <?php if (isset($_GET['comment'])) : ?>
                <div class="alert alert-success col-lg-6">
                    Votre commentaire a bien été envoyé
                </div>
            <?php endif; ?>
            <h5 class="text-primary mb-4">Laisser un commentaire</h5>
            <small>
                <p>Votre adresse de messagerie ne sera pas publiée.<br>Les champs obligatoires sont indiqués avec (*)</p>
            </small>
            <form action="" method="post">
                <?= $form->input('user', 'Nom de l\'utilisateur :* ', 'Nom'); ?>
                <?= $form->input('email', 'Email :* ', 'nom@exemple.com'); ?>
                <?= $form->textarea('comment', 'Commentaire :*', 'Votre commentaire'); ?>
                <button action="submit" class="btn btn-dark">Poster</button>
            </form>
        </div>
    </div>
<h5><small><?= $count ?> Commentaire<?php if($count > 1):?>s<?php endif;?> :</small></h5>
    <?php foreach ($comments as $com) : ?>
        <div class="card w-75 mb-2">
            <div class="card-body">
                <h6 class="card-title mb-1"><?= e($com->getUser()) ?></h6>
                <p class="text-muted"><small>Publié Le <?= $com->getDateFr() ?></small></p>
                <p class="card-text"><?= e($com->getComment()) ?></p>
            </div>
        </div>
    <?php endforeach; ?>
</div>