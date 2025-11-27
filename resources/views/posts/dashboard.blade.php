@extends('layouts.app')

@section('title', 'Dashboard - Smart Blog')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-6">
    <div class="grid lg:grid-cols-12 gap-6">
        
        <div class="lg:col-span-3">
            <div class="bg-white dark:bg-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-800 sticky top-20">
                <div class="text-center mb-6">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-20 h-20 rounded-full mx-auto mb-3">
                    @else
                        <div class="w-20 h-20 rounded-full bg-gradient-to-r from-purple-500 to-blue-500 flex items-center justify-center text-white font-bold text-2xl mx-auto mb-3">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @endif
                    <h3 class="font-bold text-lg text-gray-900 dark:text-white">{{ Auth::user()->name }}</h3>
                    <p class="text-gray-400 dark:text-gray-400 text-sm">{{ '@' . (Auth::user()->username ?? 'user') }}</p>
                </div>

                <div class="grid grid-cols-3 gap-4 mb-6 text-center">
                    <div>
                        <div class="font-bold text-purple-400">{{ $stats['total'] }}</div>
                        <div class="text-xs text-gray-400">Posts</div>
                    </div>
                    <div>
                        <div class="font-bold text-green-400">{{ Auth::user()->followers()->count() }}</div>
                        <div class="text-xs text-gray-400">Followers</div>
                    </div>
                    <div>
                        <div class="font-bold text-blue-400">{{ Auth::user()->following()->count() }}</div>
                        <div class="text-xs text-gray-400">Following</div>
                    </div>
                </div>

                <div class="space-y-2">
                    <a href="{{ route('posts.create') }}" class="block w-full bg-gradient-to-r from-purple-600 to-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:shadow-lg transition text-center">
                        Create Post
                    </a>
                    <a href="{{ route('stories.create') }}" class="block w-full bg-gray-800 text-white font-semibold py-2 px-4 rounded-lg hover:bg-gray-700 transition text-center">
                        Add Story
                    </a>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-800 space-y-2 text-sm">
                    <a href="{{ route('profile.show', Auth::user()->username ?? Auth::id()) }}" class="flex items-center space-x-2 text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-white transition">
                        <span class="material-symbols-outlined text-xl">person</span>
                        <span>My Profile</span>
                    </a>
                    <a href="{{ route('bookmarks.index') }}" class="flex items-center space-x-2 text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-white transition">
                        <span class="material-symbols-outlined text-xl">bookmark</span>
                        <span>Saved Posts</span>
                    </a>
                    <a href="{{ route('notifications.index') }}" class="flex items-center space-x-2 text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-white transition">
                        <span class="material-symbols-outlined text-xl">notifications</span>
                        <span>Notifications</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="lg:col-span-6 space-y-6">
            @if($stories->isNotEmpty())
            <div class="bg-white dark:bg-gray-900 rounded-xl p-4 border border-gray-200 dark:border-gray-800">
                <h3 class="font-bold mb-4 text-gray-900 dark:text-white">Stories</h3>
                <div class="flex space-x-4 overflow-x-auto pb-2">
                    @foreach($stories as $userId => $userStories)
                        <a href="{{ route('stories.show', $userId) }}" class="flex-shrink-0">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-purple-500 via-pink-500 to-red-500 p-0.5">
                                <div class="w-full h-full rounded-full bg-gray-900 p-0.5">
                                    @if($userStories->first()->user->avatar)
                                        <img src="{{ asset('storage/' . $userStories->first()->user->avatar) }}" class="w-full h-full rounded-full object-cover">
                                    @else
                                        <div class="w-full h-full rounded-full bg-gradient-to-r from-purple-500 to-blue-500 flex items-center justify-center text-white font-bold">
                                            {{ substr($userStories->first()->user->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <p class="text-xs text-center mt-1 truncate w-16 text-gray-700 dark:text-gray-300">{{ $userStories->first()->user->name }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- My Posts Section -->
            @if($myPosts->count() > 0)
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-xl text-gray-900 dark:text-white">My Posts</h3>
                    <a href="{{ route('posts.create') }}" class="text-purple-400 hover:text-purple-300 text-sm font-semibold">
                        + New Post
                    </a>
                </div>
                
                <div class="space-y-4">
                    @foreach($myPosts as $post)
                    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700 hover:border-purple-500/50 transition-all">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h4 class="font-semibold text-lg text-gray-900 dark:text-white">{{ $post->title }}</h4>
                                    @if($post->status === 'draft')
                                        <span class="px-3 py-1 bg-yellow-500/20 text-yellow-400 text-xs font-semibold rounded-full border border-yellow-500/30">
                                            Draft
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-green-500/20 text-green-400 text-xs font-semibold rounded-full border border-green-500/30">
                                            Published
                                        </span>
                                    @endif
                                </div>
                                
                                <p class="text-gray-600 dark:text-gray-400 text-sm mb-3 line-clamp-2">{{ Str::limit(strip_tags($post->description), 120) }}</p>
                                
                                <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-500">
                                    <span class="flex items-center space-x-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                        <span>{{ $post->reactions_count ?? 0 }}</span>
                                    </span>
                                    <span class="flex items-center space-x-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                        </svg>
                                        <span>{{ $post->all_comments_count ?? 0 }}</span>
                                    </span>
                                    <span>{{ $post->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-2 ml-4">
                                @if($post->status === 'published')
                                    <a href="{{ route('posts.show', $post->id) }}" class="p-2 text-gray-400 hover:text-purple-400 transition" title="View">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                @endif
                                <a href="{{ route('posts.edit', $post->id) }}" class="p-2 text-gray-400 hover:text-blue-400 transition" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this post?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-400 transition" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                @if($myPosts->hasPages())
                    <div class="mt-4">
                        {{ $myPosts->links() }}
                    </div>
                @endif
            </div>
            @endif

            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4">
                <h3 class="font-bold mb-4 text-gray-900 dark:text-white">Feed</h3>
            </div>

            @forelse($feed as $post)
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden">
                <div class="p-4 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('profile.show', ['user' => $post->user->username ?? $post->user->id]) }}">
                            @if($post->user->avatar)
                                <img src="{{ asset('storage/' . $post->user->avatar) }}" class="w-10 h-10 rounded-full">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-purple-500 to-blue-500 flex items-center justify-center text-white font-bold">
                                    {{ substr($post->user->name, 0, 1) }}
                                </div>
                            @endif
                        </a>
                        <div>
                            <a href="{{ route('profile.show', ['user' => $post->user->username ?? $post->user->id]) }}" class="font-semibold text-gray-900 dark:text-white hover:text-purple-600 dark:hover:text-purple-400">{{ $post->user->name }}</a>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $post->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    
                    @if($post->user->id !== Auth::id())
                        <button 
                            data-user-id="{{ $post->user->id }}"
                            data-following="{{ Auth::user()->isFollowing($post->user->id) ? 'true' : 'false' }}"
                            class="follow-btn-feed px-4 py-1.5 rounded-lg font-semibold text-sm transition-all duration-300 {{ Auth::user()->isFollowing($post->user->id) ? 'bg-gray-700 text-gray-300 hover:bg-gray-600' : 'bg-gradient-to-r from-purple-600 to-pink-600 text-white hover:from-purple-700 hover:to-pink-700' }}">
                            <span class="follow-text">{{ Auth::user()->isFollowing($post->user->id) ? 'Following' : 'Follow' }}</span>
                        </button>
                    @endif
                </div>

                @if($post->image_path)
                    <div class="relative group cursor-pointer" onclick="openImageModal('{{ asset('storage/' . $post->image_path) }}', '{{ $post->title }}')">
                        <img src="{{ asset('storage/' . $post->image_path) }}" class="w-full h-96 object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-300 flex items-center justify-center">
                            <svg class="w-16 h-16 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                            </svg>
                        </div>
                    </div>
                @endif

                <div class="p-4">
                    <div class="flex items-center space-x-6 mb-3">
                        <form action="{{ route('posts.react', $post) }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="type" value="like">
                            <button type="submit" class="flex items-center space-x-1 {{ $post->hasUserReacted(Auth::id()) ? 'text-red-500' : 'text-gray-400' }} hover:text-red-500 transition">
                                <span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' {{ $post->hasUserReacted(Auth::id()) ? '1' : '0' }};">favorite</span>
                                <span>{{ $post->reactions()->count() }}</span>
                            </button>
                        </form>

                        <a href="{{ route('blogs.show', $post) }}" class="flex items-center space-x-1 text-gray-400 hover:text-blue-500 transition">
                            <span class="material-symbols-outlined text-2xl">comment</span>
                            <span>{{ $post->allComments()->count() }}</span>
                        </a>

                        <button onclick="toggleBookmark({{ $post->id }}, this)" class="flex items-center space-x-1 {{ Auth::user()->hasBookmarked($post->id) ? 'text-yellow-500' : 'text-gray-400' }} hover:text-yellow-500 transition bookmark-btn ml-auto" data-bookmarked="{{ Auth::user()->hasBookmarked($post->id) ? 'true' : 'false' }}" title="{{ Auth::user()->hasBookmarked($post->id) ? 'Remove from bookmarks' : 'Save to bookmarks' }}">
                            <span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' {{ Auth::user()->hasBookmarked($post->id) ? '1' : '0' }};">bookmark</span>
                        </button>
                    </div>

                    <h3 class="font-bold text-lg mb-2 text-gray-900 dark:text-white">{{ $post->title }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm line-clamp-2">{{ Str::limit($post->description, 150) }}</p>
                    <a href="{{ route('blogs.show', $post) }}" class="text-purple-400 text-sm hover:underline mt-2 inline-block">Read more</a>
                </div>
            </div>
            @empty
            <div class="bg-white dark:bg-gray-900 rounded-xl p-12 text-center border border-gray-200 dark:border-gray-800">
                <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
                <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">No posts yet</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Follow users to see their posts in your feed</p>
                <a href="{{ route('search') }}" class="inline-block px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                    Discover People
                </a>
            </div>
            @endforelse

            @if($feed->hasPages())
            <div class="mt-6">
                {{ $feed->links() }}
            </div>
            @endif
        </div>

        <div class="lg:col-span-3">
            <div class="bg-white dark:bg-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-800 sticky top-20">
                <h3 class="font-bold mb-4 text-gray-900 dark:text-white">Trending Posts</h3>
                <div class="space-y-4">
                    @forelse($trendingPosts as $trending)
                    <a href="{{ route('blogs.show', $trending) }}" class="block hover:bg-gray-50 dark:hover:bg-gray-800 p-3 rounded-lg transition">
                        <h4 class="font-semibold text-sm mb-1 line-clamp-2 text-gray-900 dark:text-white">{{ $trending->title }}</h4>
                        <div class="flex items-center space-x-2 text-xs text-gray-500 dark:text-gray-400">
                            <span>{{ $trending->reactions_count }} reactions</span>
                            <span>•</span>
                            <span>{{ $trending->created_at->diffForHumans() }}</span>
                        </div>
                    </a>
                    @empty
                    <p class="text-gray-500 text-sm">No trending posts</p>
                    @endforelse
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-800">
                    <h3 class="font-bold mb-4 text-gray-900 dark:text-white">Suggested for You</h3>
                    <div class="space-y-4">
                        @forelse($suggestedUsers as $suggestedUser)
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('profile.show', $suggestedUser->username ?? $suggestedUser->id) }}">
                                @if($suggestedUser->avatar)
                                    <img src="{{ asset('storage/' . $suggestedUser->avatar) }}" class="w-10 h-10 rounded-full">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-purple-500 to-blue-500 flex items-center justify-center text-white text-sm font-bold">
                                        {{ substr($suggestedUser->name, 0, 1) }}
                                    </div>
                                @endif
                            </a>
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('profile.show', $suggestedUser->username ?? $suggestedUser->id) }}" class="block">
                                    <h4 class="font-semibold text-sm truncate text-gray-900 dark:text-white">{{ $suggestedUser->name }}</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $suggestedUser->followers_count }} followers</p>
                                </a>
                            </div>
                            <form action="{{ route('users.follow', $suggestedUser->username ?? $suggestedUser->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 text-xs font-semibold transition">
                                    Follow
                                </button>
                            </form>
                        </div>
                        @empty
                        <p class="text-gray-500 dark:text-gray-400 text-sm">No suggestions yet</p>
                        @endforelse
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-800">
                    <h3 class="font-bold mb-4 text-sm text-gray-900 dark:text-white">Quick Links</h3>
                    <div class="space-y-2 text-sm">
                        <a href="{{ route('blogs.index') }}" class="block text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-white">All Blogs</a>
                        <a href="{{ route('search') }}" class="block text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-white">Explore</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 hidden z-50 flex items-center justify-center p-4" onclick="closeImageModal()">
    <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 transition">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
    <div class="max-w-7xl max-h-full" onclick="event.stopPropagation()">
        <img id="modalImage" src="" alt="" class="max-w-full max-h-screen object-contain rounded-lg">
        <p id="modalCaption" class="text-white text-center mt-4 text-lg"></p>
    </div>
</div>

<script>
    function openImageModal(src, caption) {
        document.getElementById('imageModal').classList.remove('hidden');
        document.getElementById('modalImage').src = src;
        document.getElementById('modalCaption').textContent = caption;
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeImageModal();
    });

    document.querySelectorAll('.follow-btn-feed').forEach(button => {
        button.addEventListener('click', async function() {
            const userId = this.getAttribute('data-user-id');
            const isFollowing = this.getAttribute('data-following') === 'true';
            
            this.disabled = true;
            this.style.opacity = '0.6';
            
            try {
                const url = isFollowing 
                    ? `/users/${userId}/unfollow`
                    : `/users/${userId}/follow`;
                
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    const newFollowing = !isFollowing;
                    this.setAttribute('data-following', newFollowing ? 'true' : 'false');
                    
                    const followText = this.querySelector('.follow-text');
                    if (newFollowing) {
                        this.className = 'follow-btn-feed px-4 py-1.5 rounded-lg font-semibold text-sm transition-all duration-300 bg-gray-700 text-gray-300 hover:bg-gray-600';
                        followText.textContent = 'Following';
                    } else {
                        this.className = 'follow-btn-feed px-4 py-1.5 rounded-lg font-semibold text-sm transition-all duration-300 bg-gradient-to-r from-purple-600 to-pink-600 text-white hover:from-purple-700 hover:to-pink-700';
                        followText.textContent = 'Follow';
                    }
                }
            } catch (error) {
                console.error('Error:', error);
            } finally {
                this.disabled = false;
                this.style.opacity = '1';
            }
        });
    });

    async function toggleBookmark(postId, button) {
        const isBookmarked = button.dataset.bookmarked === 'true';
        const url = `/bookmarks/posts/${postId}`;
        const method = isBookmarked ? 'DELETE' : 'POST';
        
        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            const icon = button.querySelector('.material-symbols-outlined');
            
            if (!isBookmarked) {
                icon.style.fontVariationSettings = "'FILL' 1";
                button.classList.remove('text-gray-400');
                button.classList.add('text-yellow-500');
                button.dataset.bookmarked = 'true';
                button.title = 'Remove from bookmarks';
            } else {
                icon.style.fontVariationSettings = "'FILL' 0";
                button.classList.remove('text-yellow-500');
                button.classList.add('text-gray-400');
                button.dataset.bookmarked = 'false';
                button.title = 'Save to bookmarks';
            }
        } catch (error) {
            console.error('Error toggling bookmark:', error);
        }
    }
</script>
@endsection
