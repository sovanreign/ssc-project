<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Project::query();

            // Filter by status if provided
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            $projects = $query->withCount('tasks')
                ->orderBy('id', 'asc')
                ->get();

            return view('projects.index', [
                'projects' => $projects,
                'currentStatus' => $request->status ?? 'all'
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading projects: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            Project::create($validated);

            DB::commit();
            return redirect()->route('projects.index')
                ->with('success', 'Project created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating project: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, Project $project)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'status' => 'required|in:todo,in_progress,completed,overdue',
            ]);

            $project->update($validated);

            DB::commit();
            return redirect()->route('projects.index')
                ->with('success', 'Project updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating project: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Project $project)
    {
        try {
            DB::beginTransaction();
            
            $project->delete();
            
            DB::commit();
            return redirect()->route('projects.index')
                ->with('success', 'Project deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting project: ' . $e->getMessage());
        }
    }
} 