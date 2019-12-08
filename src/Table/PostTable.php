<?php 
namespace App\Table;

use App\PaginatedQuery;
use App\Model\Post;



class PostTable extends Table {
    
    protected $class = Post::class;
    protected $table = 'post';
    protected $sortable = ['name', 'created_at', 'id'];
    protected $dir = ["asc", "desc"];

    public function createPost(Post $post, array $categories): void
    {
        $this->pdo->beginTransaction();
        $id = $this->create([
            'name' => $post->getName(),
            'slug' => $post->getSlug(),
            'content' => $post->getContent(),
            'created_at' => $post->getCreatedAt()->format('Y-m-d H:i:s'),
        ]);
        $post->setId($id);
        $ok = $query = $this->pdo->prepare("INSERT INTO post_category SET post_id = ?, category_id = ?");
        if (!empty($categories)) {
            foreach ($categories as $category) {
                $query->execute([$post->getId(), $category]);
                if ($ok === false) {
                    throw new Exception("Impossible d'associer l'article aux Catégories");
                }
            }
        }
        $this->pdo->commit();
    }

    public function updatePost(Post $post, array $categories): void
    {
        $this->pdo->beginTransaction();
        $this->update([
            'name' => $post->getName(),
            'slug' => $post->getSlug(),
            'content' => $post->getContent(),
            'created_at' => $post->getCreatedAt()->format('Y-m-d H:i:s'),
        ], $post->getId());
        $this->pdo->exec('DELETE FROM post_category WHERE post_id = ' . $post->getId());
        $ok = $query = $this->pdo->prepare("INSERT INTO post_category SET post_id = ?, category_id = ?");
        foreach($categories as $category){
                $query->execute([$post->getId(), $category]);
                if ($ok === false) {
                    throw new Exception("Impossible d'associer l'article aux Catégories");
                }
        }
        $this->pdo->commit();
    }

    public function findPaginated ()
    {
        $paginatedQuery = new PaginatedQuery(
            "SELECT * FROM {$this->table} {$this->orderBy()}",
            "SELECT COUNT(id) FROM {$this->table}");
            $this->pdo;
            $posts = $paginatedQuery->getItems($this->class);
            return[$posts, $paginatedQuery];
    } 

    public function findPaginatedForCategory(int $categoryID)
    {
        $paginatedQuery = new PaginatedQuery(
            "SELECT p.*
             FROM {$this->table} p
             JOIN post_category pc ON pc.post_id = p.id
             WHERE pc.category_id = {$categoryID}
             ORDER BY created_at DESC",
            "SELECT COUNT(category_id) FROM post_category WHERE category_id = {$categoryID}");
            $posts = $paginatedQuery->getItems($this->class);
            return [$posts, $paginatedQuery];
    }
}