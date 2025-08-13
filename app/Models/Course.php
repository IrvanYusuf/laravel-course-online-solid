<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, "course_id");
    }
}
