<div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title text-primary"><?= e($post->getName()) ?></h5>
                <p><?= $post->getExcerpt()?></p>
                <p class="text-muted"><small>Publié Le <?= $post->getDateFr()?></small></p>
                <p>
                   <a class="btn btn-light btn-xs" href="<?= $router->url('Articles', ['id' => $post->getId(), 'slug' => $post->getSlug()])?>"><small>Lire plus</small></a>
                </p>
            </div>
        </div>