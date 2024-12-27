<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
//        return parent::toArray($request);
        return [
            'id'         => $this->id,
            'user'       => $this->user,
            'content'    => $this->content,
            'created_at' => $this->created_at->format('F j, Y \a\t g:i A'),
            'updated_at' => $this->updated_at->format('F j, Y \a\t g:i A'),
        ];
    }
}
