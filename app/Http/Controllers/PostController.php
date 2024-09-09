<?php

namespace App\Http\Controllers;

use App\Actions\Post\CreatePostAction;
use App\Helpers\ResponseJsonHelper;
use App\Http\Requests\CreatePostRequest;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    public function store(CreatePostRequest $request, CreatePostAction $action)
    {
        try {
            $post = $action->execute(
                auth()->user()->id,
                $request
            );

            return new PostResource($post);
        } catch (\Throwable $th) {
            return ResponseJsonHelper::error($th->getMessage());
        }
    }
}
