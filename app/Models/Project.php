<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

    protected $fillable = [
        'name',
        'tools',
        'cover_img_path',
        'summary',
        'repo_link',
        'standout'
    ];

}
