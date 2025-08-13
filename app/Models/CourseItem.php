<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CourseItem extends Model
{
    use HasUuids;

    protected $fillable = [
        "course_id",
        "title",
        "link_video",
        "order"
    ];
}
