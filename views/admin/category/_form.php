<form action="" method="post">
    <?= $form->input('name', 'Nom de la categorie');?>
    <?= $form->input('slug', 'URL');?>
    <button action="submit"class="btn btn-dark">
    <?php if($categorie->getId() !== null) : ?>
        Modifier
    <?php else: ?>
        Créer 
    <?php endif ;?>
            </button>
</form>