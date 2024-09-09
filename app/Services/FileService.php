<?php

namespace App\Services;

use App\Repositories\FileRepository;

class FileService {
    protected $fileRepository;

    public function __construct(FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    public function createFile(array $data)
    {
        return $this->fileRepository->create($data);
    }
}
