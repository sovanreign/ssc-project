<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        // Get all projects with their tasks and assigned users
        $projects = Project::with(['tasks', 'tasks.assignedUser'])
            ->get()
            ->map(function ($project) {
                return [
                    'id' => $project->id,
                    'name' => $project->name,
                    'completed_tasks' => $project->tasks->where('status', 'completed')->count(),
                    'status' => $project->status,
                    'start_date' => $project->start_date->format('m/d/Y'),
                    'end_date' => $project->end_date->format('m/d/Y'),
                    'assigned_members' => $project->tasks->pluck('assignedUser.name')->unique()->filter()->implode(', ')
                ];
            });

        return view('reports', compact('projects'));
    }
} 