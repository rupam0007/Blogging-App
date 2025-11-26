@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-950 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2 flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mr-4 shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path>
                            </svg>
                        </div>
                        My Bookmarks
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 ml-16">Your saved posts for later reading</p>
                </div>
                <a href="{{ route('blogs.index') }}" class="px-4 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg border border-gray-300 dark:border-gray-700 hover:border-purple-500 dark:hover:border-purple-500 transition-all">
                    Explore Posts
                </a>
            </div>
        </div>

        <!-- Bookmarks Count -->
        @if($bookmarks->total() > 0)
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 mb-6">
                <p class="text-gray-700 dark:text-gray-300">
                    <span class="text-purple-600 dark:text-purple-400 font-semibold">{{ $bookmarks->total() }}</span> 
                    {{ Str::plural('post', $bookmarks->total()) }} saved
                </p>
            </div>
        @endif

        <!-- Bookmarked Posts Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($bookmarks as $post)
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden hover:border-purple-500 hover:shadow-xl transition-all duration-300 group">
                    <!-- Post Image -->
                    <div class="relative">
                        @if($post->image_path)
                            <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                        
                        <!-- Bookmark Badge -->
                        <div class="absolute top-3 right-3">
                            <span class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
                                ✓ Saved
                            </span>
                        </div>
                    </div>

                    <!-- Post Content -->
                    <div class="p-6">
                        <!-- Title -->
                        <h3 class="text-gray-900 dark:text-white font-bold text-lg mb-2 line-clamp-2 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                            <a href="{{ route('blogs.show', $post) }}">
                                {{ $post->title }}
                            </a>
                        </h3>

                        <!-- Description -->
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-3">{{ Str::limit($post->description, 120) }}</p>

                        <!-- Author -->
                        <div class="flex items-center mb-4">
                            @if($post->user->avatar)
                                <img src="{{ asset('storage/' . $post->user->avatar) }}" alt="{{ $post->user->name }}" class="w-8 h-8 rounded-full ring-2 ring-purple-500/20">
                            @else
                                <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-md">
                                    {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="ml-2">
                                <p class="text-gray-900 dark:text-gray-300 text-sm font-medium">{{ $post->user->name }}</p>
                                <p class="text-gray-500 dark:text-gray-500 text-xs">{{ $post->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="flex items-center justify-between text-gray-600 dark:text-gray-400 text-sm mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                            <span class="flex items-center space-x-1 hover:text-red-500 transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                </svg>
                                <span>{{ $post->reactions->count() }}</span>
                            </span>
                            <span class="flex items-center space-x-1 hover:text-blue-500 transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path>
                                </svg>
                                <span>{{ $post->allComments->count() }}</span>
                            </span>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('blogs.show', $post) }}" class="flex-1 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white text-center px-4 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-md hover:shadow-lg">
                                Read Post
                            </a>
                            <form method="POST" action="{{ route('bookmarks.destroy', $post) }}" class="remove-bookmark-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-gray-200 dark:bg-gray-800 hover:bg-red-500 dark:hover:bg-red-600 text-gray-700 dark:text-gray-300 hover:text-white px-4 py-2.5 rounded-lg text-sm font-medium transition-all" title="Remove Bookmark">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3">
                    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-16 text-center">
                        <div class="w-24 h-24 bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/20 dark:to-pink-900/20 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path>
                            </svg>
                        </div>
                        <h3 class="text-gray-900 dark:text-white text-2xl font-bold mb-3">No bookmarks yet</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">Start bookmarking posts you want to read later and they'll appear here</p>
                        <a href="{{ route('blogs.index') }}" class="inline-flex items-center space-x-2 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-all shadow-md hover:shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span>Explore Posts</span>
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($bookmarks->hasPages())
            <div class="mt-8">
                {{ $bookmarks->links() }}
            </div>
        @endif
    </div>
</div>

<script>
document.querySelectorAll('.remove-bookmark-form').forEach(form => {
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        if (!confirm('Remove this bookmark?')) return;
        
        const response = await fetch(form.action, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            form.closest('.group').remove();
            
            if (document.querySelectorAll('.remove-bookmark-form').length === 0) {
                location.reload();
            }
        }
    });
});
</script>
@endsection
