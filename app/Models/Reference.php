<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    use HasFactory;
 protected $fillable = [
        'category_id',
        'tutorial_id',
        'title',
        'content',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tutorial()
    {
        return $this->belongsTo(Tutorial::class);
    }

}