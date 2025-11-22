<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Blog Posts</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-900 text-gray-100 p-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-4xl font-bold mb-10 text-center text-blue-400">Latest Blogs</h1>
        
        <div class="flex justify-end mb-6 space-x-4">
            @auth
                <a href="{{ route('dashboard') }}" class="bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700">
                    Go to Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700">
                    Log In to Post
                </a>
            @endauth
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($posts as $post)
                <div class="bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-700 flex flex-col">
                    @if ($post->image_path)
                        <div class="relative group cursor-pointer" onclick="openImageModal('{{ asset('storage/' . $post->image_path) }}', '{{ $post->title }}')">
                            <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}" class="h-48 w-full object-cover">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-300 flex items-center justify-center">
                                <svg class="w-12 h-12 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                </svg>
                            </div>
                        </div>
                    @endif
                    
                    @if ($post->video_path)
            <div class="w-full mb-8">
                <h3 class="text-xl font-semibold mb-3 text-blue-300">Video Content</h3>
                <video controls class="w-full rounded-xl shadow-lg border-2 border-gray-700">
                    <source src="{{ asset('storage/' . $post->video_path) }}" type="video/mp4"> 
                    Your browser does not support the video tag.
                </video>
            </div>
        @endif
                    <div class="p-6 flex flex-col flex-grow">
                        <h2 class="text-xl font-bold mb-3 text-white">{{ $post->title }}</h2>
                        
                        <p class="text-gray-400 text-sm mb-4 flex-grow">{{ Str::limit($post->description, 100) }}</p>
                        
                        @auth
                        <div class="flex items-center space-x-6 mb-4 pb-4 border-b border-gray-700">
                            <button onclick="toggleReaction({{ $post->id }}, this)" class="flex items-center space-x-1 {{ $post->hasUserReacted(Auth::id()) ? 'text-red-500' : 'text-gray-400' }} hover:text-red-500 transition reaction-btn" data-reacted="{{ $post->hasUserReacted(Auth::id()) ? 'true' : 'false' }}">
                                <svg class="w-6 h-6" fill="{{ $post->hasUserReacted(Auth::id()) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                <span class="reaction-count">{{ $post->reactions()->count() }}</span>
                            </button>

                            <a href="{{ route('blogs.show', $post) }}" class="flex items-center space-x-1 text-gray-400 hover:text-blue-500 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <span>{{ $post->allComments()->count() }}</span>
                            </a>

                            <button onclick="toggleBookmark({{ $post->id }}, this)" class="flex items-center space-x-1 {{ Auth::user()->hasBookmarked($post->id) ? 'text-yellow-500' : 'text-gray-400' }} hover:text-yellow-500 transition bookmark-btn ml-auto" data-bookmarked="{{ Auth::user()->hasBookmarked($post->id) ? 'true' : 'false' }}" title="{{ Auth::user()->hasBookmarked($post->id) ? 'Remove from bookmarks' : 'Save to bookmarks' }}">
                                <svg class="w-6 h-6" fill="{{ Auth::user()->hasBookmarked($post->id) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                </svg>
                            </button>
                        </div>
                        @endauth
                        
                        <a href="{{ route('blogs.show', $post) }}" class="text-blue-500 hover:text-blue-400 font-semibold text-sm mb-4">
                            Read More &rarr;
                        </a>
                        
                        <div class="mt-auto pt-4 border-t border-gray-700">
                            <div class="flex items-center justify-between">
                                <div class="text-xs text-gray-500">
                                    By: <a href="{{ $post->user->username ? route('profile.show', $post->user->username) : '#' }}" class="text-blue-400 hover:text-blue-300">{{ $post->user->name ?? 'Guest' }}</a> on {{ $post->created_at->format('M d, Y') }}
                                </div>
                                @auth
                                    @if($post->user->id !== Auth::id() && $post->user->username)
                                        <button 
                                            data-user-id="{{ $post->user->id }}"
                                            data-following="{{ Auth::user()->isFollowing($post->user->id) ? 'true' : 'false' }}"
                                            class="follow-btn-blog px-3 py-1 rounded-lg font-semibold text-xs transition-all duration-300 {{ Auth::user()->isFollowing($post->user->id) ? 'bg-gray-700 text-gray-300 hover:bg-gray-600' : 'bg-gradient-to-r from-purple-600 to-pink-600 text-white hover:from-purple-700 hover:to-pink-700' }}">
                                            <span class="follow-text">{{ Auth::user()->isFollowing($post->user->id) ? 'Following' : 'Follow' }}</span>
                                        </button>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 col-span-full">No blog posts found. Be the first to post!</p>
            @endforelse
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

        // Handle follow buttons in blog cards
        document.querySelectorAll('.follow-btn-blog').forEach(button => {
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
                            this.className = 'follow-btn-blog px-3 py-1 rounded-lg font-semibold text-xs transition-all duration-300 bg-gray-700 text-gray-300 hover:bg-gray-600';
                            followText.textContent = 'Following';
                        } else {
                            this.className = 'follow-btn-blog px-3 py-1 rounded-lg font-semibold text-xs transition-all duration-300 bg-gradient-to-r from-purple-600 to-pink-600 text-white hover:from-purple-700 hover:to-pink-700';
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

        @auth
        async function toggleReaction(postId, button) {
            try {
                const isReacted = button.dataset.reacted === 'true';
                const url = isReacted ? `/posts/${postId}/unreact` : `/posts/${postId}/react`;
                const method = isReacted ? 'DELETE' : 'POST';
                const body = isReacted ? null : JSON.stringify({ type: 'like' });

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: body
                });

                const data = await response.json();
                const countSpan = button.querySelector('.reaction-count');
                const svg = button.querySelector('svg');
                
                if (!isReacted) {
                    svg.setAttribute('fill', 'currentColor');
                    button.classList.remove('text-gray-400');
                    button.classList.add('text-red-500');
                    button.dataset.reacted = 'true';
                } else {
                    svg.setAttribute('fill', 'none');
                    button.classList.remove('text-red-500');
                    button.classList.add('text-gray-400');
                    button.dataset.reacted = 'false';
                }
                
                countSpan.textContent = data.reaction_count;
            } catch (error) {
                console.error('Error toggling reaction:', error);
            }
        }

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
                const svg = button.querySelector('svg');
                
                if (!isBookmarked) {
                    svg.setAttribute('fill', 'currentColor');
                    button.classList.remove('text-gray-400');
                    button.classList.add('text-yellow-500');
                    button.dataset.bookmarked = 'true';
                    button.title = 'Remove from bookmarks';
                } else {
                    svg.setAttribute('fill', 'none');
                    button.classList.remove('text-yellow-500');
                    button.classList.add('text-gray-400');
                    button.dataset.bookmarked = 'false';
                    button.title = 'Save to bookmarks';
                }
            } catch (error) {
                console.error('Error toggling bookmark:', error);
            }
        }
        @endauth
    </script>
</body>
</html>