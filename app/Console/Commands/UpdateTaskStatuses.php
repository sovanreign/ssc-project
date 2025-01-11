<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;

class UpdateTaskStatuses extends Command
{
    protected $signature = 'tasks:update-statuses';
    protected $description = 'Update task statuses based on their dates';

    public function handle()
    {
        $tasks = Task::where('status', '!=', 'completed')->get();
        
        foreach ($tasks as $task) {
            $task->updateStatusBasedOnDates();
        }

        $this->info('Task statuses have been updated successfully.');
    }
} 