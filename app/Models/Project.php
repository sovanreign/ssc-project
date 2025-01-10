<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    // Add accessor for task count if needed
    public function getTaskCountAttribute()
    {
        return $this->tasks()->count();
    }
} 