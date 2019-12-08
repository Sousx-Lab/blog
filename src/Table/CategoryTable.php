<?php 
namespace App\Table;

use App\PaginatedQuery;
use App\Model\Category;
use \PDO;

class CategoryTable extends Table {

    protected $table = 'category';
    protected $class = Category::class;
    protected $sortable = ['name', 'id'];
    protected $dir = ["asc", "desc"];

    public function findCategoryForPost(int $postID)/**cherche les catégorie dans la vue post(show) */
    {
        $query = $this->pdo->prepare(
                'SELECT c.id, c.slug, c.name
                 FROM post_category pc 
                 JOIN category c ON pc.category_id = c.id
                 WHERE pc.post_id = :id');
                 $query->execute(['id' => $postID]);
                 $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
                 $result = $query->fetchAll();
                 return $result;

    }

    public function findPaginatedCategory ()/**Pagination des catégories dans les catégories(show) */
    {
        $paginatedQuery = new PaginatedQuery(
            "SELECT * FROM {$this->table} {$this->orderBy()} ",
            "SELECT COUNT(id) FROM {$this->table}");
            $this->pdo;
            $category = $paginatedQuery->getItems($this->class);
            return[$category, $paginatedQuery];
    }

    public function list(): array
    {
        $categories = $this->queryAndFetchAll("SELECT * FROM {$this->table} ORDER BY name ASC");
        $results = [];
        foreach($categories as $category){
            $results[$category->getId()] = $category->getName();
        }
        return $results;
    }

    /**
     * @param App\Model\Post[] $post
     */
    public function hydratePosts(array $posts)
    {
        $postsByID = [];
        foreach($posts as $post)
        {
            $postsByID[$post->getId()] = $post;    
        }
        $categories = $this->pdo
                ->query('SELECT c.*, pc.post_id
                FROM post_category pc
                JOIN category c ON c.id = pc.category_id
                WHERE pc.post_id IN (' . implode(',', array_keys($postsByID)) . ')')
                ->fetchAll(PDO::FETCH_CLASS, $this->class);
        foreach($categories as $category)
        {
            $postsByID[$category->getPostId()]->addCategory($category);
        }
    }
    
    public function createCategory(Category $categorie): void
    {
        $this->pdo->beginTransaction();
        $this->create([
            'name' => $categorie->getName(),
            'slug' => $categorie->getSlug(),
        ]);
        $this->pdo->commit();
    }

    public function updateCategory(Category $categorie): void
    {
        $this->pdo->beginTransaction();
        $this->update([
            'name' => $categorie->getName(),
            'slug' => $categorie->getSlug(),
        ],$categorie->getId());
        $this->pdo->commit();
    }

}