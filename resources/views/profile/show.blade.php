@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-gray-900">
    <!-- Cover Photo -->
    <div class="relative h-80 bg-gradient-to-r from-purple-600 to-pink-600">
        @if($user->cover_photo)
            <img src="{{ Storage::url($user->cover_photo) }}" alt="Cover" class="w-full h-full object-cover object-center">
        @endif
        <div class="absolute inset-0 bg-black bg-opacity-30"></div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20">
        <!-- Profile Header -->
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-6 mb-6">
            <div class="flex flex-col md:flex-row items-center md:items-end space-y-4 md:space-y-0 md:space-x-6">
                <!-- Avatar -->
                <div class="relative">
                    @if($user->avatar)
                        <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-32 h-32 rounded-full border-4 border-gray-800">
                    @else
                        <div class="w-32 h-32 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full border-4 border-gray-800 flex items-center justify-center text-white text-4xl font-bold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <!-- User Info -->
                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-3xl font-bold text-white mb-1">{{ $user->name }}</h1>
                    @if($user->username)
                        <p class="text-gray-400 mb-3">{{ '@' . $user->username }}</p>
                    @endif
                    @if($user->bio)
                        <p class="text-gray-300 mb-4">{{ $user->bio }}</p>
                    @endif
                    
                    <!-- Stats -->
                    <div class="flex justify-center md:justify-start space-x-6 text-sm">
                        <div>
                            <span class="text-white font-semibold">{{ $posts->count() }}</span>
                            <span class="text-gray-400 ml-1">Posts</span>
                        </div>
                        <a href="{{ route('users.followers', ['user' => $user->username ?? $user->id]) }}" class="hover:text-purple-400">
                            <span class="text-white font-semibold">{{ $user->followers->count() }}</span>
                            <span class="text-gray-400 ml-1">Followers</span>
                        </a>
                        <a href="{{ route('users.following', ['user' => $user->username ?? $user->id]) }}" class="hover:text-purple-400">
                            <span class="text-white font-semibold">{{ $user->following->count() }}</span>
                            <span class="text-gray-400 ml-1">Following</span>
                        </a>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex space-x-3">
                    @auth
                        @if(auth()->id() === $user->id)
                            <a href="{{ route('profile.edit') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition-all">
                                Edit Profile
                            </a>
                        @else
                            <button 
                                id="followButton"
                                data-user-id="{{ $user->id }}"
                                data-following="{{ $isFollowing ? 'true' : 'false' }}"
                                class="px-6 py-2 rounded-lg font-medium transition-all {{ $isFollowing ? 'bg-gray-700 hover:bg-gray-600 text-white' : 'bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white' }}">
                                <span class="follow-text">{{ $isFollowing ? 'Following' : 'Follow' }}</span>
                            </button>
                            <span id="followersCount" class="hidden">{{ $user->followers->count() }}</span>
                        @endif
                    @endauth
                </div>
            </div>
        </div>

        <!-- Posts Grid -->
        <div>
            <h2 class="text-2xl font-bold text-white mb-6">Posts</h2>
            
            @if($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($posts as $post)
                        <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden hover:border-purple-500 transition-all group">
                            @if($post->image_path)
                                <img src="{{ Storage::url($post->image_path) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gradient-to-br from-purple-600 to-pink-600 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif

                            <div class="p-6">
                                <h3 class="text-white font-semibold text-lg mb-2 line-clamp-2 group-hover:text-purple-400 transition-colors">
                                    <a href="{{ route('blogs.show', $post) }}">{{ $post->title }}</a>
                                </h3>
                                <p class="text-gray-400 text-sm mb-4 line-clamp-2">{{ Str::limit($post->description, 100) }}</p>
                                
                                <div class="flex items-center justify-between text-gray-400 text-sm">
                                    <span>{{ $post->created_at->format('M d, Y') }}</span>
                                    <div class="flex space-x-4">
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
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-gray-800 rounded-xl border border-gray-700 p-12 text-center">
                    <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                    <p class="text-gray-400">No posts yet</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const followButton = document.getElementById('followButton');
    
    if (followButton) {
        followButton.addEventListener('click', async function() {
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
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
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
                        this.className = 'px-6 py-2 rounded-lg font-medium transition-all bg-gray-700 hover:bg-gray-600 text-white';
                        followText.textContent = 'Following';
                    } else {
                        this.className = 'px-6 py-2 rounded-lg font-medium transition-all bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white';
                        followText.textContent = 'Follow';
                    }
                    
                    const followerLinks = document.querySelectorAll('a[href*="followers"]');
                    if (followerLinks.length > 0 && data.followers_count !== undefined) {
                        followerLinks.forEach(link => {
                            const countSpan = link.querySelector('.font-semibold');
                            if (countSpan) {
                                countSpan.textContent = data.followers_count;
                            }
                        });
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            } finally {
                this.disabled = false;
                this.style.opacity = '1';
            }
        });
    }
});
</script>
@endsection
