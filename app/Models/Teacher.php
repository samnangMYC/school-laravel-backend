<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'fname',
        'lname',
        'phone',
        'dob',
        'gender',
        'email',
        'join_date',
        'education',
        'description',
        'status',
        'image',
        'department_id',
    ];


    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
