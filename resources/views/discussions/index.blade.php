@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Discussion</h2>
        <div>
            <button onclick="openAddAgendaModal()" class="bg-[#1e3a8a] text-white px-4 py-2 rounded-lg shadow hover:bg-blue-900 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                ADD AGENDA
            </button>
        </div>
    </div>

    <!-- Agenda List -->
    <div class="grid gap-6">
        @forelse($discussions as $discussion)
            <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                <div class="mb-4">
                    <h3 class="text-xl font-bold text-[#1e3a8a] mb-6">AGENDA {{ $loop->iteration }}</h3>
                    <div class="space-y-2">
                        <p><span class="font-semibold">What:</span> {{ $discussion->title }}</p>
                        <p><span class="font-semibold">Where:</span> {{ $discussion->location }}</p>
                        <p><span class="font-semibold">When:</span> {{ $discussion->date->format('F d, Y') }}</p>
                        <p><span class="font-semibold">Description:</span> {{ $discussion->description }}</p>
                    </div>
                </div>
                <div class="flex justify-center gap-4">
                    <a href="{{ route('discussions.show', $discussion) }}" 
                       class="bg-[#1e3a8a] text-white px-6 py-2 rounded-lg hover:bg-blue-900 transition-colors duration-300 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        START CONVERSATION
                    </a>
                    @if(auth()->user()->role === 'admin')
                        <form action="{{ route('discussions.destroy', $discussion) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this discussion?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors duration-300 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                DELETE
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow-lg p-6 text-center text-gray-500">
                No agendas found. Click "ADD AGENDA" to create one.
            </div>
        @endforelse
    </div>

    <!-- Add Agenda Modal -->
    <div id="addAgendaModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Add New Agenda</h3>
                <button onclick="closeAddAgendaModal()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form action="{{ route('discussions.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">What:</label>
                        <input type="text" name="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Where:</label>
                        <input type="text" name="location" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">When:</label>
                        <input type="date" name="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description:</label>
                        <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required></textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-[#1e3a8a] text-white px-4 py-2 rounded-lg hover:bg-blue-900 transition-colors duration-300">
                        ADD AGENDA
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openAddAgendaModal() {
        document.getElementById('addAgendaModal').classList.remove('hidden');
        document.getElementById('addAgendaModal').classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeAddAgendaModal() {
        document.getElementById('addAgendaModal').classList.add('hidden');
        document.getElementById('addAgendaModal').classList.remove('flex');
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside
    document.getElementById('addAgendaModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeAddAgendaModal();
        }
    });

    // Close modal when pressing escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAddAgendaModal();
        }
    });
</script>
@endsection 