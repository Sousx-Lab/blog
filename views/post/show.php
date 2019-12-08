<?php

use App\Db;
use App\Table\CategoryTable;
use App\Table\PostTable;


$id = (int)$params['id'];
$slug = $params['slug'];

$pdo = Db::getPDO();
$post = (new PostTable($pdo))->find($id);
$url = $router->url('Articles', ['slug' => $post->getSlug(), 'id' => $id]);
if ($post->getSlug() !== $slug) {
    http_response_code(301);
    header('Location: ' . $url);
}
$categories = (new CategoryTable($pdo))->findCategoryForPost($post->getId());
$title = e($post->getName());
?>

<h1 class="text-primary"><?= e($post->getName()) ?></h1>
    <p class="text-muted"><small>Publié le <?= $post->getDateFr() ?></small></p>
    <div class="mb-3">
        <small>Tags:</small>
        <?php foreach ($categories as $k => $categorie) : ?>
            <?php if ($k > 0) : ?>
                <small>|</small>
                <?php endif; ?>
            <?php $link = $router->url('category', ['id' => $categorie->getId(), 'slug' => $categorie->getSlug()]); ?>
        <small><a href="<?= $link ?>"><?= $categorie->getName() ?></a></small>
    <?php endforeach; ?>
</div> 
  <div class=" mb-5">
    <p> <?= $post->getFormattedContent() ?></p>
 </div>
<?php require('comment.php') ;?>