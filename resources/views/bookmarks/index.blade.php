@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-white mb-2">
                <svg class="w-10 h-10 inline-block mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path>
                </svg>
                My Bookmarks
            </h1>
            <p class="text-gray-400">Your saved posts for later reading</p>
        </div>

        <!-- Bookmarks Count -->
        @if($bookmarkedPosts->total() > 0)
            <div class="bg-gray-800 rounded-lg border border-gray-700 p-4 mb-6">
                <p class="text-gray-300">
                    <span class="text-purple-400 font-semibold">{{ $bookmarkedPosts->total() }}</span> 
                    {{ Str::plural('post', $bookmarkedPosts->total()) }} saved
                </p>
            </div>
        @endif

        <!-- Bookmarked Posts Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($bookmarkedPosts as $post)
                <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden hover:border-purple-500 transition-all group">
                    <!-- Post Image -->
                    <div class="relative">
                        @if($post->image_path)
                            <img src="{{ Storage::url($post->image_path) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-purple-600 to-pink-600 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                        
                        <!-- Bookmark Badge -->
                        <div class="absolute top-3 right-3">
                            <span class="bg-purple-600 text-white px-2 py-1 rounded-full text-xs font-semibold">
                                Saved
                            </span>
                        </div>
                    </div>

                    <!-- Post Content -->
                    <div class="p-6">
                        <!-- Title -->
                        <h3 class="text-white font-semibold text-lg mb-2 line-clamp-2 group-hover:text-purple-400 transition-colors">
                            <a href="{{ route('blogs.show', $post) }}">
                                {{ $post->title }}
                            </a>
                        </h3>

                        <!-- Description -->
                        <p class="text-gray-400 text-sm mb-4 line-clamp-3">{{ Str::limit($post->description, 120) }}</p>

                        <!-- Author -->
                        <div class="flex items-center mb-4">
                            @if($post->user->avatar)
                                <img src="{{ Storage::url($post->user->avatar) }}" alt="{{ $post->user->name }}" class="w-8 h-8 rounded-full">
                            @else
                                <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                    {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="ml-2">
                                <p class="text-gray-300 text-sm">{{ $post->user->name }}</p>
                                <p class="text-gray-500 text-xs">{{ $post->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="flex items-center justify-between text-gray-400 text-sm mb-4 pb-4 border-b border-gray-700">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"></path>
                                </svg>
                                {{ $post->reactions->count() }}
                            </span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $post->allComments->count() }}
                            </span>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('blogs.show', $post) }}" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white text-center px-4 py-2 rounded-lg text-sm font-medium transition-all">
                                Read Post
                            </a>
                            <form method="POST" action="{{ route('bookmarks.destroy', $post) }}" class="remove-bookmark-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-gray-700 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all" title="Remove Bookmark">
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
                    <div class="bg-gray-800 rounded-xl border border-gray-700 p-12 text-center">
                        <svg class="w-20 h-20 text-gray-600 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path>
                        </svg>
                        <h3 class="text-white text-xl font-semibold mb-2">No bookmarks yet</h3>
                        <p class="text-gray-400 mb-6">Start bookmarking posts you want to read later</p>
                        <a href="{{ route('blogs.index') }}" class="inline-block bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-medium transition-all">
                            Explore Posts
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($bookmarkedPosts->hasPages())
            <div class="mt-8">
                {{ $bookmarkedPosts->links() }}
            </div>
        @endif
    </div>
</div>

<script>
// Optional: Add AJAX for removing bookmarks without page reload
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
            
            // Reload if no bookmarks left
            if (document.querySelectorAll('.remove-bookmark-form').length === 0) {
                location.reload();
            }
        }
    });
});
</script>
@endsection
