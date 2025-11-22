@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-gray-900 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-4xl font-bold text-white mb-2">
                    <svg class="w-10 h-10 inline-block mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                    </svg>
                    Notifications
                </h1>
                <p class="text-gray-400">Stay updated with your activity</p>
            </div>
            @if($notifications->where('is_read', false)->count() > 0)
                <form method="POST" action="{{ route('notifications.mark-all-read') }}" id="mark-all-read">
                    @csrf
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all">
                        Mark All as Read
                    </button>
                </form>
            @endif
        </div>

        <!-- Notifications List -->
        <div class="space-y-3">
            @forelse($notifications as $notification)
                <div class="bg-gray-800 rounded-xl border border-gray-700 p-4 hover:border-purple-500 transition-all {{ $notification->is_read ? 'opacity-60' : '' }}">
                    <div class="flex items-start space-x-4">
                        <!-- From User Avatar -->
                        <div class="flex-shrink-0">
                            @if($notification->fromUser && $notification->fromUser->avatar)
                                <img src="{{ Storage::url($notification->fromUser->avatar) }}" alt="{{ $notification->fromUser->name }}" class="w-12 h-12 rounded-full">
                            @elseif($notification->fromUser)
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($notification->fromUser->name, 0, 1)) }}
                                </div>
                            @else
                                <div class="w-12 h-12 bg-gray-700 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Notification Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <!-- Notification Type Icon & Content -->
                                    <div class="flex items-center mb-1">
                                        @if($notification->type === 'follow')
                                            <svg class="w-5 h-5 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>
                                            </svg>
                                        @elseif($notification->type === 'reaction')
                                            <svg class="w-5 h-5 text-pink-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                            </svg>
                                        @elseif($notification->type === 'comment')
                                            <svg class="w-5 h-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path>
                                            </svg>
                                        @elseif($notification->type === 'mention')
                                            <svg class="w-5 h-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M14.243 5.757a6 6 0 10-.986 9.284 1 1 0 111.087 1.678A8 8 0 1118 10a3 3 0 01-4.8 2.401A4 4 0 1114 10a1 1 0 102 0c0-1.537-.586-3.07-1.757-4.243zM12 10a2 2 0 10-4 0 2 2 0 004 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        @endif
                                        
                                        <p class="text-white text-sm">
                                            {{ $notification->content }}
                                        </p>
                                    </div>

                                    <!-- Time -->
                                    <p class="text-gray-400 text-xs">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>

                                <!-- Unread Indicator -->
                                @if(!$notification->is_read)
                                    <div class="ml-4">
                                        <span class="block w-2 h-2 bg-purple-500 rounded-full"></span>
                                    </div>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center space-x-3 mt-3">
                                @if($notification->notifiable_type === 'App\Models\Post')
                                    <a href="{{ route('blogs.show', $notification->notifiable_id) }}" class="text-purple-400 hover:text-purple-300 text-sm font-medium">
                                        View Post →
                                    </a>
                                @elseif($notification->notifiable_type === 'App\Models\Comment')
                                    @php
                                        $comment = \App\Models\Comment::find($notification->notifiable_id);
                                    @endphp
                                    @if($comment)
                                        <a href="{{ route('blogs.show', $comment->post_id) }}#comment-{{ $notification->notifiable_id }}" class="text-purple-400 hover:text-purple-300 text-sm font-medium">
                                            View Comment →
                                        </a>
                                    @endif
                                @elseif($notification->notifiable_type === 'App\Models\User')
                                    <a href="{{ route('profile.show', $notification->notifiable_id) }}" class="text-purple-400 hover:text-purple-300 text-sm font-medium">
                                        View Profile →
                                    </a>
                                @endif

                                @if(!$notification->is_read)
                                    <form method="POST" action="{{ route('notifications.mark-read', $notification) }}" class="mark-as-read">
                                        @csrf
                                        <button type="submit" class="text-gray-400 hover:text-white text-xs">
                                            Mark as read
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-gray-800 rounded-xl border border-gray-700 p-12 text-center">
                    <svg class="w-20 h-20 text-gray-600 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                    </svg>
                    <h3 class="text-white text-xl font-semibold mb-2">No notifications yet</h3>
                    <p class="text-gray-400">When you get notifications, they'll show up here</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="mt-8">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>

<script>
// AJAX for marking as read
document.querySelectorAll('.mark-as-read').forEach(form => {
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const notification = form.closest('.bg-gray-800');
            notification.classList.add('opacity-60');
            form.remove();
        }
    });
});

// AJAX for mark all as read
const markAllForm = document.getElementById('mark-all-read');
if (markAllForm) {
    markAllForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const response = await fetch(markAllForm.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            location.reload();
        }
    });
}
</script>
@endsection
