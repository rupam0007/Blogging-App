@extends('layouts.app')

@section('title', 'Search Results')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Search Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2">Search Results</h1>
        @if($query)
            <p class="text-gray-400">Showing results for "<span class="text-purple-400">{{ $query }}</span>"</p>
        @else
            <p class="text-gray-400">Enter a search query to find posts and users</p>
        @endif
    </div>

    <!-- Search Bar -->
    <div class="mb-8">
        <form action="{{ route('search') }}" method="GET" class="w-full">
            <div class="flex gap-4">
                <div class="flex-1 relative">
                    <input 
                        type="text" 
                        name="q" 
                        value="{{ $query }}"
                        placeholder="Search posts, users..." 
                        class="w-full bg-gray-800 border border-gray-700 rounded-xl px-5 py-3 pl-12 text-gray-100 placeholder-gray-500 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all"
                        autofocus
                    >
                    <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-300">
                    Search
                </button>
            </div>
        </form>
    </div>

    @if($query)
        <!-- Filter Tabs -->
        <div class="flex items-center gap-4 mb-8 border-b border-gray-700">
            <a href="{{ route('search', ['q' => $query, 'type' => 'all']) }}" 
               class="pb-4 px-4 font-semibold transition-colors {{ $type === 'all' ? 'text-purple-400 border-b-2 border-purple-400' : 'text-gray-400 hover:text-gray-300' }}">
                All
            </a>
            <a href="{{ route('search', ['q' => $query, 'type' => 'posts']) }}" 
               class="pb-4 px-4 font-semibold transition-colors {{ $type === 'posts' ? 'text-purple-400 border-b-2 border-purple-400' : 'text-gray-400 hover:text-gray-300' }}">
                Posts ({{ $postsCount }})
            </a>
            <a href="{{ route('search', ['q' => $query, 'type' => 'users']) }}" 
               class="pb-4 px-4 font-semibold transition-colors {{ $type === 'users' ? 'text-purple-400 border-b-2 border-purple-400' : 'text-gray-400 hover:text-gray-300' }}">
                Users ({{ $usersCount }})
            </a>
        </div>

        <!-- Posts Results -->
        @if(($type === 'all' || $type === 'posts') && $posts->count() > 0)
            <div class="mb-12">
                <h2 class="text-2xl font-bold mb-6">Posts</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($posts as $post)
                        <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden hover:border-purple-500/50 transition-all duration-300 group">
                            @if($post->image_path)
                                <a href="{{ route('posts.show', $post->id) }}" class="block aspect-video overflow-hidden">
                                    <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                </a>
                            @endif
                            
                            <div class="p-5">
                                <a href="{{ route('posts.show', $post->id) }}" class="block mb-3">
                                    <h3 class="text-xl font-bold text-white group-hover:text-purple-400 transition-colors line-clamp-2">{{ $post->title }}</h3>
                                </a>
                                
                                <p class="text-gray-400 text-sm mb-4 line-clamp-3">{{ Str::limit(strip_tags($post->description), 150) }}</p>
                                
                                <!-- Post Meta -->
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ $post->user->username ? route('profile.show', $post->user->username) : '#' }}" class="flex items-center space-x-2 text-gray-400 hover:text-purple-400 transition-colors">
                                            @if($post->user->avatar)
                                                <img src="{{ asset('storage/' . $post->user->avatar) }}" alt="{{ $post->user->name }}" class="w-6 h-6 rounded-full">
                                            @else
                                                <div class="w-6 h-6 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-xs font-bold">
                                                    {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <span>{{ $post->user->name }}</span>
                                        </a>
                                    </div>
                                    
                                    <div class="flex items-center space-x-3 text-gray-500">
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($posts->hasPages())
                    <div class="mt-8">
                        {{ $posts->appends(['q' => $query, 'type' => $type])->links() }}
                    </div>
                @endif
            </div>
        @elseif($type === 'posts')
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-gray-500">No posts found matching "{{ $query }}"</p>
            </div>
        @endif

        <!-- Users Results -->
        @if(($type === 'all' || $type === 'users') && $users->count() > 0)
            <div class="mb-12">
                <h2 class="text-2xl font-bold mb-6">Users</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($users as $user)
                        <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 hover:border-purple-500/50 transition-all duration-300">
                            <div class="flex items-center space-x-4 mb-4">
                                <a href="{{ $user->username ? route('profile.show', $user->username) : '#' }}">
                                    @if($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-full object-cover border-2 border-purple-500/50">
                                    @else
                                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-2xl font-bold border-2 border-purple-500/50">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                    @endif
                                </a>
                                
                                <div class="flex-1 min-w-0">
                                    <a href="{{ $user->username ? route('profile.show', $user->username) : '#' }}" class="block">
                                        <h3 class="text-lg font-bold text-white hover:text-purple-400 transition-colors truncate">{{ $user->name }}</h3>
                                        @if($user->username)
                                            <p class="text-gray-400 text-sm truncate">{{ '@' . $user->username }}</p>
                                        @endif
                                    </a>
                                </div>
                            </div>
                            
                            @if($user->bio)
                                <p class="text-gray-400 text-sm mb-4 line-clamp-2">{{ $user->bio }}</p>
                            @endif
                            
                            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                <span><span class="font-semibold text-gray-400">{{ $user->posts_count ?? 0 }}</span> posts</span>
                                <span><span class="font-semibold text-gray-400">{{ $user->followers_count ?? 0 }}</span> followers</span>
                            </div>
                            
                            @auth
                                @if($user->id !== auth()->id() && $user->username)
                                    <button 
                                        data-user-id="{{ $user->id }}"
                                        data-following="{{ auth()->user()->isFollowing($user->id) ? 'true' : 'false' }}"
                                        class="follow-btn w-full px-4 py-2 rounded-lg font-semibold transition-all duration-300 {{ auth()->user()->isFollowing($user->id) ? 'bg-gray-700 text-gray-300 hover:bg-gray-600' : 'bg-gradient-to-r from-purple-600 to-pink-600 text-white hover:from-purple-700 hover:to-pink-700' }}">
                                        <span class="follow-text">{{ auth()->user()->isFollowing($user->id) ? 'Following' : 'Follow' }}</span>
                                    </button>
                                @endif
                            @endauth
                        </div>
                    @endforeach
                </div>

                @if($users->hasPages())
                    <div class="mt-8">
                        {{ $users->appends(['q' => $query, 'type' => $type])->links() }}
                    </div>
                @endif
            </div>
        @elseif($type === 'users')
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <p class="text-gray-500">No users found matching "{{ $query }}"</p>
            </div>
        @endif

        <!-- No Results -->
        @if(($type === 'all' && $posts->count() === 0 && $users->count() === 0))
            <div class="text-center py-16">
                <svg class="w-24 h-24 mx-auto text-gray-600 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <h3 class="text-2xl font-bold text-white mb-3">No results found</h3>
                <p class="text-gray-400 mb-6">We couldn't find anything matching "{{ $query }}"</p>
                <p class="text-gray-500 text-sm">Try different keywords or check your spelling</p>
            </div>
        @endif
    @else
        <!-- Empty State - No Search Query -->
        <div class="text-center py-16">
            <svg class="w-24 h-24 mx-auto text-gray-600 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <h3 class="text-2xl font-bold text-white mb-3">Start Searching</h3>
            <p class="text-gray-400">Enter a search term to find posts and users</p>
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Follow/Unfollow functionality
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.follow-btn').forEach(button => {
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
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        const newFollowing = !isFollowing;
                        this.setAttribute('data-following', newFollowing ? 'true' : 'false');
                        
                        const followText = this.querySelector('.follow-text');
                        if (newFollowing) {
                            this.className = 'follow-btn w-full px-4 py-2 rounded-lg font-semibold transition-all duration-300 bg-gray-700 text-gray-300 hover:bg-gray-600';
                            followText.textContent = 'Following';
                        } else {
                            this.className = 'follow-btn w-full px-4 py-2 rounded-lg font-semibold transition-all duration-300 bg-gradient-to-r from-purple-600 to-pink-600 text-white hover:from-purple-700 hover:to-pink-700';
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
    });
</script>
@endpush
@endsection
