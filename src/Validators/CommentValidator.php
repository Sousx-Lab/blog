<?php

namespace App\Validators;

use App\Table\CommentTable;

class CommentValidator extends AbstractValidator
{

    public function __construct(array $data, CommentTable $table, ?int $commentID = null)
    {
        parent::__construct($data);
        $this->validator->labels(array(
            'user' => 'Le nom',
            'email' => 'Cette adresse',
            'comment' => 'Le commentaire'
        ));
        $this->validator->rule('required', ['user', 'email', 'comment']);
        $this->validator->rule('lengthBetween', ['user'], 3, 10);
        $this->validator->rule('lengthBetween', ['comment'], 10, 1000);
        $this->validator->rule('email', 'email');
    }
}
