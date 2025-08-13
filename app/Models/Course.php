<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasUuids;

    protected $fillable = [
        "title",
        "price",
        "description",
        "duration",
        "instructor_id",
        "category_id"
    ];
}
