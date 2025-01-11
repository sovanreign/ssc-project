@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Discussion Header -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h2 class="text-2xl font-bold text-[#1e3a8a] mb-4">AGENDA 1</h2>
                <div class="space-y-2">
                    <p><span class="font-semibold">What:</span> {{ $discussion->title }}</p>
                    <p><span class="font-semibold">Where:</span> {{ $discussion->location }}</p>
                    <p><span class="font-semibold">When:</span> {{ $discussion->date->format('F d, Y') }}</p>
                    <p><span class="font-semibold">Description:</span> {{ $discussion->description }}</p>
                </div>
            </div>
            <button onclick="closeDiscussion()" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Chat Section -->
    <div class="bg-white rounded-lg shadow-lg">
        <!-- Messages -->
        <div class="h-[500px] overflow-y-auto p-6 space-y-4" id="messages">
            @foreach($messages as $message)
                <div class="flex items-start gap-4 {{ $message->user_id === auth()->id() ? 'flex-row-reverse' : '' }}">
                    <!-- User Avatar -->
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="text-xs text-center mt-1">{{ $message->user->role }}</div>
                    </div>

                    <!-- Message Content -->
                    <div class="flex-1 {{ $message->user_id === auth()->id() ? 'text-right' : '' }}">
                        <div class="flex items-center gap-2 mb-1 {{ $message->user_id === auth()->id() ? 'justify-end' : '' }}">
                            <span class="font-semibold">{{ $message->user->name }}</span>
                            <span class="text-xs text-gray-500">{{ $message->created_at->format('g:i A') }}</span>
                        </div>
                        <div class="inline-block rounded-lg px-4 py-2 {{ $message->user_id === auth()->id() ? 'bg-blue-100' : 'bg-gray-100' }}">
                            {{ $message->message }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Message Input -->
        <div class="p-4 border-t">
            <form action="{{ route('discussions.messages.store', $discussion) }}" method="POST" class="flex gap-4">
                @csrf
                <input type="text" name="message" class="flex-1 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Type your message..." required>
                <button type="submit" class="bg-[#1e3a8a] text-white px-6 py-2 rounded-lg hover:bg-blue-900">
                    Send
                </button>
            </form>
        </div>

        <!-- End Discussion Button -->
        <div class="p-4 border-t text-center">
            <button onclick="closeDiscussion()" class="text-gray-600 hover:text-gray-800 underline">
                End Conversation?
            </button>
        </div>
    </div>
</div>

<script>
    // Scroll to bottom of messages on load
    document.addEventListener('DOMContentLoaded', function() {
        const messages = document.getElementById('messages');
        messages.scrollTop = messages.scrollHeight;
    });

    function closeDiscussion() {
        window.location.href = "{{ route('discussions.index') }}";
    }
</script>
@endsection 