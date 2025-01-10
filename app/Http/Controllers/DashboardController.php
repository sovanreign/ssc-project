<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $projects = Project::withCount('tasks')
            ->with(['tasks' => function($query) {
                $query->select('id', 'project_id');
            }])
            ->latest()
            ->take(5)
            ->get();

        // Get total counts for the stats bar
        $totalTasks = Task::count();
        $totalProjects = Project::count();

        return view('dashboard', compact('projects', 'totalTasks', 'totalProjects'));
    }
} 