<?php
$pdo = Db::getPDO();
$query = $pdo->prepare("SELECT * FROM category WHERE id = :id");
$query->execute(['id' => $id]);
$query->setFetchMode(PDO::FETCH_CLASS, Category::class);
$category = $query->fetch();

/** @var Category|false */
if($category === false){
    throw new Exception('Aucune categorie ne correspond à cette ID');
}

if($category->getSlug() !== $slug){
    $url = $router->url('category', ['slug' => $category->getSlug(), 'id' => $id]);
    http_response_code(301);
    header('Location: ' . $url);
}
$title = "{$category->getName()}";

$currentPage = URL::getPositiveInt('page', 1);
$count = (int)$pdo
                ->query('SELECT COUNT(category_id) FROM post_category WHERE category_id = ' . $category->getId())
                ->fetch(PDO::FETCH_NUM)[0];
$perPage = 12;
$pages = ceil($count / $perPage);


$offset = $perPage * ($currentPage - 1);
$query = $pdo->query("
        SELECT p.*
        FROM post p
        JOIN post_category pc ON pc.post_id = p.id
        WHERE pc.category_id = {$category->getId()}
        ORDER BY created_at DESC
        LIMIT $perPage OFFSET $offset
        ");
$posts = $query->fetchAll(PDO::FETCH_CLASS, Post::class);
$link = $router->url('category', ['id' => $category->getId(), 'slug' => $category->getSlug()]);
