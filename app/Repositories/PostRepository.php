<?php

namespace App\Repositories;

use App\Models\Post;

class PostRepository {
    protected $model;

    public function __construct(Post $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }
}
