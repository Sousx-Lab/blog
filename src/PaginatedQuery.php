<?php 

namespace App;
use \PDO;

class PaginatedQuery {

    private $query;
    private $queryCount;
    private $pdo;
    private $perPage;
    private $count;
    private $items;
    
    public function __construct(string $query, string $queryCount, ?\PDO $pdo = null, int $perPage = 12)
    {
        $this->query = $query;
        $this->queryCount = $queryCount;
        $this->pdo = $pdo ?: Db::getPDO();
        $this->perPage = $perPage;
    }

    public function getItems(string $classMapping): array
    {
        if($this->items === null)
        {
         $currentPage = $this->getCurrentPage();
        $pages = $this->getPages();
        if($currentPage > $pages){
            throw new \Exception ("Cette page n'existe pas");
        }
        $offset = $this->perPage * ($currentPage - 1);
        $this->items = $this->pdo->query($this->query . " LIMIT {$this->perPage} OFFSET $offset")
                                 ->fetchAll(PDO::FETCH_CLASS, $classMapping);
        }
        return $this->items;
    }

    private function getCurrentPage(): int
    {
        return URL::getPositiveInt('page', 1);
    }

    private function getPages(): int
    {
        if($this->count === null){
            $this->count = (int)$this->pdo
            ->query($this->queryCount)
            ->fetch(PDO::FETCH_NUM)[0];
        }
        
        return (int)ceil($this->count / $this->perPage);
    }

    public function previousLink(string $link)
    {
        $currentPage = $this->getCurrentPage();
        if($currentPage <= 1) return null;
        if($currentPage > 2) $link .= "?page=" . ($currentPage - 1);
        return<<<HTML
        <a href="{$link}" class="btn btn-dark"><small>&laquo; Page précédente</small></a>
        HTML;
    }

    public function nextLink(string $link)
    {
        $currentPage = $this->getCurrentPage();
        $pages = $this->getPages();
        if($currentPage >= $pages) return null;
        $link .= "?page=" . ($currentPage + 1);
        return<<<HTML
        <a href="{$link}" class="btn btn-dark ml-auto"><small>Page suivante &raquo;</small></a>
        HTML;
    }

}