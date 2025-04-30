<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BlogPostCategory extends Pivot
{
    use HasFactory;

    protected $table = 'blog_post_categories';

    protected $fillable = [
        'post_id',
        'category_id',
    ];
}