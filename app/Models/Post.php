<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\ElasticsearchService;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'title',
        'excerpt',
        'description',
        'image',
        'keywords',
        'meta_title',
        'meta_description',
        'published_at',
        'status'
    ];

//    protected static function booted()
//    {
//        static::saved(function ($post){
//            $elasticsearch = app(ElasticsearchService::class);
//
//            $elasticsearch->index('posts', [
//                'title'       => $post->title,
//                'excerpt'     => $post->excerpt,
//                'description' => $post->description,
//                'keywords'    => $post->keywords,
//            ]);
//        });
//
//        static::deleted(function ($post){
//            $elasticsearch = app(ElasticsearchService::class);
//
//            $elasticsearch->delete('posts', $post->id);
//        });
//    }

    public function scopePublished($query)
    {
        return $query->where('status', 1);
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 0);
    }

    public function getImageAttribute($value)
    {
        if (!empty($value)){
            return asset('storage/' . $value);
        }
        return null;
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
