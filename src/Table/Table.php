<?php 
namespace App\Table;

use \PDO;


abstract class Table {

    protected $pdo;
    protected $table = null;
    protected $class = null;

    public function __construct(PDO $pdo)
    {
       $this->pdo = $pdo;

    }

    public function find(int $id)
    {

        $query = $this->pdo->prepare('SELECT * FROM ' . $this->table . ' WHERE id = :id');
        $query->execute(['id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        $result = $query->fetch();
        if($result === false){
            throw new NotFoundException($this->table, $id);
        }
        return $result;

    }

    /**
     * Vérifie si une valeur existe dans une table
     * @param string $field Champs à rechercher
     * @param mixed $value Valeur associée au champs 
     */
    public function exists (string $field, $value, ?int $except = null): bool
    {
        $sql = "SELECT COUNT(id) FROM {$this->table} WHERE $field = ?";
        $params = [$value];
        if($except !== null){
            $sql .= " AND id != ?";
            $params[] = $except;
        }
        $query = $this->pdo->prepare($sql);
        $query->execute($params);
        return (int)$query->fetch(PDO::FETCH_NUM)[0] > 0;
    }

    public function create(array $data): int
    {  
      $sqlFields = [];
      foreach($data as $key => $value){
          $sqlFields[] = "$key = :$key";
      }
      $query = $this->pdo->prepare("INSERT INTO {$this->table} SET " . implode(', ', $sqlFields));
      $ok = $query->execute($data);
      if($ok === false){
          throw new \Exception("Impossible de crée un nouvel element dans la {$this->table}");
      }
      return (int)$this->pdo->lastInsertId();
    }

    public function update(array $data, int $id): void
    { 
        $sqlFields = [];
        foreach($data as $key => $value){
            $sqlFields[] = "$key = :$key";
        }
      $query = $this->pdo->prepare("UPDATE {$this->table} SET " . implode(', ', $sqlFields) . " WHERE id = :id");
      $ok = $query->execute(array_merge($data, ['id' => $id]));
      if($ok === false){
          throw new \Exception("Impossible d'editer cette element");
      }

    }

    public function delete(int $id): void
    {
      $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
      $ok = $query->execute([$id]);
      if($ok === false){
          throw new \Exception("Impossible de supprimer l'article $id");
      }

    }

    public function all(): array
    {
        return $this->queryAndFetchAll("SELECT * FROM {$this->table} {$this->orderBy()}");
    }

    public function queryAndFetchAll($sql)
    {
        $query = $this->pdo->prepare($sql);
        $query->execute();
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        $result = $query->fetchAll();
        return $result;
    }

    public function orderBy(): string
    {
        $sort = "id ";
        $direction = "DESC";
        if(!empty($_GET['sort']) && $_GET['dir']){
            if(in_array($_GET['sort'], $this->sortable))
            $sort = $_GET["sort"];
            if(in_array($_GET['dir'], $this->dir))
            $direction = $_GET["dir"];

        }
        return " ORDER BY " . $sort  . " " .  $direction;
    }

}
