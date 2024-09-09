<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'user_id'      => $this->user_id,
            'title'        => $this->title,
            'subtitle'     => $this->subtitle,
            'thumbnail_id' => $this->thumbnail_id,
            'body'         => $this->body,
            'author'       => [
                'first_name' => $this->user->first_name,
                'last_name'  => $this->user->last_name,
                'thumbnail'  => $this->user->thumbnail,
            ],
            'thumbnail' => !is_null($this->file) ? $this->file->storage_file_path : null,
        ];
    }
}
