@extends('layouts.app')

@section('content')
<div class="p-6 mt-20">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">PROJECT</h2>
        <button id="addProjectBtn" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-all duration-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Project
        </button>
    </div>

    <!-- Project Filters -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="flex border-b">
            <button class="px-6 py-3 text-blue-600 border-b-2 border-blue-600 font-medium">All</button>
            <button class="px-6 py-3 text-gray-500 hover:text-blue-600 hover:border-b-2 hover:border-blue-600">To Do</button>
            <button class="px-6 py-3 text-gray-500 hover:text-blue-600 hover:border-b-2 hover:border-blue-600">Overdue</button>
            <button class="px-6 py-3 text-gray-500 hover:text-blue-600 hover:border-b-2 hover:border-blue-600">Completed</button>
        </div>
    </div>

    <!-- Projects Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full whitespace-nowrap">
            <thead>
                <tr class="bg-[#1e3a8a] text-white">
                    <th class="px-6 py-4 text-center">#</th>
                    <th class="px-6 py-4 text-left">Project</th>
                    <th class="px-6 py-4 text-center">Number of Task</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-6 py-4 text-center text-blue-600 font-medium">1</td>
                    <td class="px-6 py-4 text-blue-600 font-medium">Project 1</td>
                    <td class="px-6 py-4 text-center text-blue-600 font-medium">5</td>
                </tr>
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-6 py-4 text-center text-blue-600 font-medium">2</td>
                    <td class="px-6 py-4 text-blue-600 font-medium">Project 2</td>
                    <td class="px-6 py-4 text-center text-blue-600 font-medium">6</td>
                </tr>
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-6 py-4 text-center text-blue-600 font-medium">3</td>
                    <td class="px-6 py-4 text-blue-600 font-medium">Project 3</td>
                    <td class="px-6 py-4 text-center text-blue-600 font-medium">4</td>
                </tr>
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-6 py-4 text-center text-blue-600 font-medium">4</td>
                    <td class="px-6 py-4 text-blue-600 font-medium">Project 4</td>
                    <td class="px-6 py-4 text-center text-blue-600 font-medium">5</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Project Modal -->
<div id="addProjectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">Add New Project</h3>
            <button class="text-gray-500 hover:text-gray-700" onclick="closeModal()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form action="{{ route('projects.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Project Name</label>
                    <input type="text" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Start Date</label>
                        <input type="date" name="start_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">End Date</label>
                        <input type="date" name="end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50" onclick="closeModal()">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Create Project</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const modal = document.getElementById('addProjectModal');
    const addProjectBtn = document.getElementById('addProjectBtn');

    function openModal() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    addProjectBtn.addEventListener('click', openModal);

    // Close modal when clicking outside
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal();
        }
    });

    // Close modal when pressing escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
</script>
@endpush
@endsection 