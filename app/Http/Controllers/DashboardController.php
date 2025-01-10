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
            ->latest()
            ->take(5)
            ->get();

        // Get total counts
        $totalTasks = Task::count();
        $totalProjects = Project::count();

        return view('dashboard', compact('projects', 'totalTasks', 'totalProjects'));
    }
} 