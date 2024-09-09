<?php

namespace App\Actions\Post;

use App\Models\Post;
use App\Services\FileService;
use App\Services\PostService;
use App\Services\UploadService;

class CreatePostAction {
    protected $uploadService;
    protected $fileService;
    protected $postService;

    public function __construct(
        UploadService $uploadService,
        FileService $fileService,
        PostService $postService,
    )
    {
        $this->uploadService = $uploadService;
        $this->fileService = $fileService;
        $this->postService = $postService;
    }

    public function execute($userId, $requestData): Post
    {
        $post = $this->postService->createPost(array_merge(
            $requestData->only(['title', 'subtitle', 'body']),
            ['user_id' => $userId]
        ));

        if($post) {
            $thumbnail = $requestData->file('thumbnail');
            if(!empty($thumbnail)) {
                $savedThumbnail = $this->uploadService->saveToLocal($thumbnail);

                $file = $this->fileService->createFile(array_merge(
                    $savedThumbnail,
                    [
                        'owner_type' => 'App\Models\Post',
                        'owner_id'   => $post->id
                    ]

                ));
                $post->update(['thumbnail_id' => $file->id]) ;
            }
        }

        return $post;
    }
}
