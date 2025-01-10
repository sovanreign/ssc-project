<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    protected $attributes = [
        'status' => 'todo',
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function getTaskCountAttribute()
    {
        return $this->tasks()->count();
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'todo' => 'gray',
            'in_progress' => 'yellow',
            'completed' => 'green',
            'overdue' => 'red',
            default => 'gray',
        };
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($project) {
            if (!$project->status) {
                $project->status = 'todo';
            }
        });
    }
} 