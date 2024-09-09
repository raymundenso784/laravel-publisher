<?php

namespace App\Repositories;

use App\Models\File;

class FileRepository {
    protected $model;

    public function __construct(File $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }
}
