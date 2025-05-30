<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    // One-to-Many relationship: Department has many Teachers
    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class);
    }
}
