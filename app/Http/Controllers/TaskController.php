<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Task::query();

            // Filter by status if provided
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            $tasks = $query->orderBy('id', 'asc')->get();
            $projects = Project::orderBy('name')->get();
            $users = User::orderBy('name')->get();

            return view('tasks.index', [
                'tasks' => $tasks,
                'projects' => $projects,
                'users' => $users,
                'currentStatus' => $request->status ?? 'all'
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading tasks: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'project_id' => 'required|exists:projects,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'assigned_to' => 'required|exists:users,id',
            ]);

            Task::create($validated);

            DB::commit();
            return redirect()->route('tasks.index')
                ->with('success', 'Task created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating task: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, Task $task)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'project_id' => 'required|exists:projects,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'assigned_to' => 'required|exists:users,id',
                'status' => 'required|in:todo,in_progress,completed,overdue',
            ]);

            $task->update($validated);

            DB::commit();
            return redirect()->route('tasks.index')
                ->with('success', 'Task updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating task: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Task $task)
    {
        try {
            DB::beginTransaction();
            
            $task->delete();
            
            DB::commit();
            return redirect()->route('tasks.index')
                ->with('success', 'Task deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting task: ' . $e->getMessage());
        }
    }

    public function show(Task $task)
    {
        return response()->json([
            'id' => $task->id,
            'name' => $task->name,
            'description' => $task->description,
            'project_name' => $task->project->name,
            'assigned_to' => $task->assignedUser->name,
            'due_date' => $task->end_date->format('M d, Y'),
            'status' => $task->status,
        ]);
    }

    public function markAsComplete(Task $task)
    {
        try {
            $task->update([
                'status' => 'completed'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Task marked as complete successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error marking task as complete'
            ], 500);
        }
    }
} 