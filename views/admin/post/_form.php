<form action="" method="post">
    <?= $form->input('name', 'Titre de l\'article');?>
    <?= $form->input('slug', 'URL');?>
    <?= $form->input('created_at', 'Date de publication');?>
    <?= $form->select('categories_ids','Catégories:', $categories);?>
    <?= $form->textarea('content', 'Contenue') ;?>
    <button action="submit"class="btn btn-dark">
    <?php if($post->getId() !== null) : ?>
        Modifier
    <?php else: ?>
        Créer 
    <?php endif ;?>
            </button>
</form>
<?= $form->summerNote('content')?>