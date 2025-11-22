@extends('layouts.app')

@section('title', $user->name . '\'s Followers')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Header with Back Button -->
    <div class="mb-8 flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('profile.show', $user->username ?? $user->id) }}" class="text-gray-400 hover:text-purple-400 transition-colors">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold">
                    @if($user->id === auth()->id())
                        Your Followers
                    @else
                        {{ $user->name }}'s Followers
                    @endif
                </h1>
                <p class="text-gray-400 mt-1">{{ $followers->total() }} {{ Str::plural('follower', $followers->total()) }}</p>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="mb-6">
        <form action="{{ route('users.followers', $user->username ?? $user->id) }}" method="GET" class="w-full">
            <div class="relative">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Search followers..." 
                    class="w-full bg-gray-800 border border-gray-700 rounded-xl px-5 py-3 pl-12 text-gray-100 placeholder-gray-500 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all"
                >
                <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                
                @if(request('search'))
                    <a href="{{ route('users.followers', $user->username ?? $user->id) }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Followers List -->
    @if($followers->count() > 0)
        <div class="space-y-3">
            @foreach($followers as $follower)
                <div class="bg-gray-800 border border-gray-700 rounded-xl p-5 hover:border-purple-500/50 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <!-- User Info -->
                        <div class="flex items-center space-x-4 flex-1 min-w-0">
                            <a href="{{ route('profile.show', $follower->username ?? $follower->id) }}" class="flex-shrink-0">
                                @if($follower->avatar)
                                    <img src="{{ asset('storage/' . $follower->avatar) }}" alt="{{ $follower->name }}" class="w-16 h-16 rounded-full object-cover border-2 border-purple-500/50">
                                @else
                                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-xl font-bold border-2 border-purple-500/50">
                                        {{ strtoupper(substr($follower->name, 0, 2)) }}
                                    </div>
                                @endif
                            </a>

                            <div class="flex-1 min-w-0">
                                <a href="{{ route('profile.show', $follower->username ?? $follower->id) }}" class="block">
                                    <h3 class="text-lg font-semibold text-white hover:text-purple-400 transition-colors truncate">
                                        {{ $follower->name }}
                                    </h3>
                                    @if($follower->username)
                                        <p class="text-gray-400 text-sm truncate">{{ '@' . $follower->username }}</p>
                                    @endif
                                </a>
                                
                                @if($follower->bio)
                                    <p class="text-gray-500 text-sm mt-1 line-clamp-2">{{ $follower->bio }}</p>
                                @endif

                                <!-- User Stats -->
                                <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                                    <span>
                                        <span class="font-semibold text-gray-400">{{ $follower->posts_count }}</span> posts
                                    </span>
                                    <span>
                                        <span class="font-semibold text-gray-400">{{ $follower->followers_count }}</span> followers
                                    </span>
                                    <span>
                                        <span class="font-semibold text-gray-400">{{ $follower->following_count }}</span> following
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        @auth
                            @if($follower->id !== auth()->id())
                                <div class="ml-4 flex-shrink-0">
                                    <button 
                                        onclick="toggleFollow({{ $follower->id }}, this)"
                                        data-following="{{ auth()->user()->isFollowing($follower->id) ? 'true' : 'false' }}"
                                        class="follow-btn px-6 py-2 rounded-lg font-semibold transition-all duration-300 {{ auth()->user()->isFollowing($follower->id) ? 'bg-gray-700 text-gray-300 hover:bg-gray-600' : 'bg-gradient-to-r from-purple-600 to-pink-600 text-white hover:from-purple-700 hover:to-pink-700' }}">
                                        <span class="follow-text">{{ auth()->user()->isFollowing($follower->id) ? 'Following' : 'Follow Back' }}</span>
                                    </button>
                                </div>
                            @else
                                <span class="ml-4 px-6 py-2 bg-gray-700 text-gray-400 rounded-lg font-semibold">You</span>
                            @endif
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($followers->hasPages())
            <div class="mt-8">
                {{ $followers->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="bg-gray-800 border border-gray-700 rounded-2xl p-12 text-center">
            <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-gray-700/50 flex items-center justify-center">
                <svg class="w-12 h-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            
            @if(request('search'))
                <h3 class="text-2xl font-bold text-white mb-3">No followers found</h3>
                <p class="text-gray-400 mb-6">
                    No followers match "{{ request('search') }}"
                </p>
                <a href="{{ route('users.followers', $user->username ?? $user->id) }}" class="inline-block px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-300">
                    Clear Search
                </a>
            @else
                <h3 class="text-2xl font-bold text-white mb-3">No followers yet</h3>
                <p class="text-gray-400 mb-6">
                    @if($user->id === auth()->id())
                        When people follow you, they'll appear here.
                    @else
                        {{ $user->name }} doesn't have any followers yet.
                    @endif
                </p>
                @if($user->id === auth()->id())
                    <a href="{{ route('explore') }}" class="inline-block px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-300">
                        Discover Users
                    </a>
                @endif
            @endif
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Toggle Follow/Unfollow
    async function toggleFollow(userId, button) {
        const isFollowing = button.getAttribute('data-following') === 'true';
        const followText = button.querySelector('.follow-text');
        
        // Disable button during request
        button.disabled = true;
        button.style.opacity = '0.6';
        
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
                
                // Toggle state
                const newFollowing = !isFollowing;
                button.setAttribute('data-following', newFollowing ? 'true' : 'false');
                
                // Update button appearance
                if (newFollowing) {
                    button.className = 'follow-btn px-6 py-2 rounded-lg font-semibold transition-all duration-300 bg-gray-700 text-gray-300 hover:bg-gray-600';
                    followText.textContent = 'Following';
                } else {
                    button.className = 'follow-btn px-6 py-2 rounded-lg font-semibold transition-all duration-300 bg-gradient-to-r from-purple-600 to-pink-600 text-white hover:from-purple-700 hover:to-pink-700';
                    followText.textContent = 'Follow Back';
                }
            } else {
                alert('An error occurred. Please try again.');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        } finally {
            // Re-enable button
            button.disabled = false;
            button.style.opacity = '1';
        }
    }
</script>
@endpush
@endsection