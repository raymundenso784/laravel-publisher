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
        if(!isset($data['published'])) {
            $data['published'] = 0;
        }

        return $this->model->create($data);
    }
}
