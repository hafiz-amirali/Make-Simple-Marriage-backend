<?php

namespace App\Models;

use Carbon\Carbon;

class Article extends \Illuminate\Database\Eloquent\Model
{

    protected $dateFormat = 'U';
    protected $fillable = [
        "title",
        "article",
        "author_name",
        "author_detail",
        "slug",
        "image",
        "status"
    ];
}