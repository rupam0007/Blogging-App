@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-4xl font-bold text-white mb-2">Comment Management</h1>
                <p class="text-gray-400">Moderate user comments and replies</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="bg-gray-800 hover:bg-gray-700 text-white px-4 py-2 rounded-lg border border-gray-700 transition-all">
                ← Back to Dashboard
            </a>
        </div>

        <!-- Comments List -->
        <div class="space-y-4">
            @forelse($comments as $comment)
                <div class="bg-gray-800 rounded-xl border border-gray-700 p-6 hover:border-purple-500 transition-all">
                    <div class="flex items-start space-x-4">
                        <!-- User Avatar -->
                        <div class="flex-shrink-0">
                            @if($comment->user->avatar)
                                <img src="{{ Storage::url($comment->user->avatar) }}" alt="{{ $comment->user->name }}" class="w-12 h-12 rounded-full">
                            @else
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <!-- Comment Content -->
                        <div class="flex-1 min-w-0">
                            <!-- User Info & Date -->
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <a href="{{ route('profile.show', $comment->user) }}" class="text-white font-semibold hover:text-purple-400">
                                        {{ $comment->user->name }}
                                    </a>
                                    @if($comment->user->username)
                                        <span class="text-gray-400 text-sm">{{ '@' . $comment->user->username }}</span>
                                    @endif
                                </div>
                                <span class="text-gray-400 text-sm">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>

                            <!-- Comment Text -->
                            <p class="text-gray-300 mb-3">{{ $comment->content }}</p>

                            <!-- Post Reference -->
                            <div class="bg-gray-900 rounded-lg p-3 mb-3">
                                <p class="text-gray-500 text-xs mb-1">Comment on:</p>
                                <a href="{{ route('blogs.show', $comment->post) }}" class="text-purple-400 hover:text-purple-300 text-sm font-medium">
                                    {{ $comment->post->title }}
                                </a>
                            </div>

                            <!-- Reply Info -->
                            @if($comment->parent_id)
                                <div class="flex items-center text-gray-400 text-sm mb-3">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                    </svg>
                                    Reply to {{ $comment->parent->user->name }}
                                </div>
                            @endif

                            <!-- Actions -->
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('blogs.show', $comment->post) }}#comment-{{ $comment->id }}" class="text-blue-400 hover:text-blue-300 text-sm flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View Context
                                </a>
                                
                                <form method="POST" action="{{ route('admin.comments.delete', $comment) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this comment?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 text-sm flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Delete Comment
                                    </button>
                                </form>

                                @if($comment->replies->count() > 0)
                                    <span class="text-gray-500 text-sm">
                                        {{ $comment->replies->count() }} {{ Str::plural('reply', $comment->replies->count()) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-gray-800 rounded-xl border border-gray-700 p-12 text-center">
                    <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <p class="text-gray-400 text-lg">No comments found</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($comments->hasPages())
            <div class="mt-8">
                {{ $comments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
