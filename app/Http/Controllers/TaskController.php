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
            $query = Task::with(['project', 'assignedUser']);

            // Update statuses before filtering
            Task::where('status', '!=', 'completed')->get()->each->updateStatusBasedOnDates();

            // Filter by status if provided
            if ($request->has('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }

            $tasks = $query->orderBy('created_at', 'desc')->get();
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
                'assigned_to' => 'required|exists:users,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $task = Task::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'project_id' => $validated['project_id'],
                'assigned_to' => $validated['assigned_to'],
                'status' => 'todo',
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
            ]);

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

    public function rateTask(Task $task, Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'rating' => 'required|integer|min:1|max:5'
            ]);

            $task->update([
                'rating' => $validated['rating']
            ]);

            // Update user's stars based on completed tasks and ratings
            $user = $task->assignedUser;
            $totalStars = $user->tasks()
                ->where('status', 'completed')
                ->sum('rating');
            
            $user->update([
                'total_stars' => $totalStars
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Task rated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error rating task: ' . $e->getMessage()
            ], 500);
        }
    }
} 