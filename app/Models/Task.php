<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'project_id',
        'start_date',
        'end_date',
        'assigned_to',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    protected $attributes = [
        'status' => 'todo',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
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
        
        static::creating(function ($task) {
            if (!$task->status) {
                $task->status = 'todo';
            }
        });
    }
} 