<?php
require '../vendor/autoload.php';
require '../commands/db-conf.php';
$faker = Faker\Factory::create('fr_FR');

$pdo = new PDO($db_dsn, $db_user, $db_pass, $options);

$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
$pdo->exec('TRUNCATE TABLE post_category');
$pdo->exec('TRUNCATE TABLE post');
$pdo->exec('TRUNCATE TABLE category');
$pdo->exec('TRUNCATE TABLE users');
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
$posts = [];
$categories = [];

for ($i = 0; $i < 50; $i++){

    $pdo->exec("INSERT INTO post SET name='{$faker->sentence()}', slug='$faker->slug', created_at='{$faker->date} {$faker->time}', content='{$faker->paragraphs(rand(3,15), true)}'");
    $posts[] = $pdo->lastInsertId();
}

for ($i = 0; $i < 5; $i++){

    $pdo->exec("INSERT INTO category SET name='{$faker->sentence(5)}', slug='$faker->slug'");
    $categories[] = $pdo->lastInsertId();
}

foreach($posts as $post){
    $randomCategories = $faker->randomElements($categories, rand(0, count($categories)));
     foreach($randomCategories as $category){
        $pdo->exec("INSERT INTO post_category SET post_id=$post, category_id=$category");
     }
}

$password = password_hash('admin', PASSWORD_BCRYPT);
$pdo->exec("INSERT INTO users SET username='admin', password='$password'");