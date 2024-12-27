<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

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
            'id'                => $this->id,
            'user_id'           => $this->user_id,
            'title'            => $this->title,
            'excerpt'          => $this->excerpt,
            'description'      => $this->description,
            'image'            => $this->image,
            'keywords'         => $this->keywords,
            'meta_title'       => $this->meta_title,
            'meta_description' => $this->meta_description,
            'published_at'     => $this->published_at,
            'comments'         => CommentResource::collection($this->comments)
        ];
    }
}
