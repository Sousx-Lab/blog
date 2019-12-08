<?php

namespace App\Table;


use App\Model\Comment;
use PDO;

class CommentTable extends Table
{

    protected $class = Comment::class;
    protected $table = 'comment';
    protected $sortable = ['created_at'];
    protected $dir = ["asc", "desc"];


    public function createComment(Comment $comment, int $postID)
    {
        $this->pdo->beginTransaction();
        $id = $this->create([
            'user' => $comment->getUser(),
            'email' => $comment->getEmail(),
            'comment' => $comment->getComment(),
            'created_at' => $comment->getCreatedAt()->format('Y-m-d H:i:s')
        ]);
        $comment->setId($id);
        $ok = $query = $this->pdo->prepare("INSERT INTO comment_post SET post_id = :postID, comment_id = :commentID");
            $query->execute(['postID' => $postID, 'commentID' => $comment->getId()]);
            if ($ok === false) {
                throw new Exception("Impossible d'jouter un commentaire a cette article");
            }
            $this->pdo->commit();
    }

    public function findCommentPost(int $postID)
    {
        $query = $this->pdo->query("SELECT COUNT(comment_id) FROM comment_post WHERE post_id = {$postID}");
        $count = $query->fetch(PDO::FETCH_NUM)[0];
        if($count !== null){
             $query = $this->pdo->prepare("SELECT c.*
             FROM {$this->table} c
             JOIN comment_post cp ON cp.comment_id = c.id
             WHERE cp.post_id = :postID
             ORDER BY created_at DESC");
             $query->execute(['postID' => $postID]);
             $results = $query->fetchAll(PDO::FETCH_CLASS, $this->class);
        }
        return [$results, (int)$count];
    }
}
