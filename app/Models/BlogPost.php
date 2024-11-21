<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;


class BlogPost extends Model
{
    use HasFactory, Searchable;

    protected $fillable = ['title', 'content', 'user_id'];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

     public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
    
}
