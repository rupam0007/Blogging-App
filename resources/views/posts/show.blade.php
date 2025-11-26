<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $post->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-900 text-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-gray-800 p-10 rounded-xl shadow-2xl border border-gray-700">
        <a href="{{ route('blogs.index') }}" class="text-blue-400 hover:text-blue-300 mb-6 block">&larr; Back to All Blogs</a>
        
        <h1 class="text-5xl font-extrabold mb-4 text-white">{{ $post->title }}</h1>
        <p class="text-sm text-gray-500 mb-8">
            Published by: <span class="text-blue-400">{{ $post->user->name ?? 'Guest' }}</span> on {{ $post->created_at->format('M d, Y') }}
        </p>
        
        @if ($post->image_path)
            <div class="relative group cursor-pointer mb-8" onclick="openImageModal('{{ asset('storage/' . $post->image_path) }}', '{{ $post->title }}')">
                <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}" class="w-full h-96 object-cover rounded-xl">
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-300 flex items-center justify-center rounded-xl">
                    <svg class="w-16 h-16 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                    </svg>
                </div>
            </div>
        @endif
        
        @auth
        <div class="flex items-center space-x-8 mb-8 pb-6 border-b border-gray-700">
            <button onclick="toggleReaction({{ $post->id }}, this)" class="flex items-center space-x-2 {{ $post->hasUserReacted(Auth::id()) ? 'text-red-500' : 'text-gray-400' }} hover:text-red-500 transition reaction-btn text-lg" data-reacted="{{ $post->hasUserReacted(Auth::id()) ? 'true' : 'false' }}">
                <svg class="w-8 h-8" fill="{{ $post->hasUserReacted(Auth::id()) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                <span class="reaction-count font-semibold">{{ $post->reactions()->count() }}</span>
                <span>Likes</span>
            </button>

            <div class="flex items-center space-x-2 text-gray-400 text-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <span class="font-semibold">{{ $post->allComments()->count() }}</span>
                <span>Comments</span>
            </div>

            <button onclick="toggleBookmark({{ $post->id }}, this)" class="flex items-center space-x-2 {{ Auth::user()->hasBookmarked($post->id) ? 'text-yellow-500' : 'text-gray-400' }} hover:text-yellow-500 transition bookmark-btn text-lg ml-auto" data-bookmarked="{{ Auth::user()->hasBookmarked($post->id) ? 'true' : 'false' }}" title="{{ Auth::user()->hasBookmarked($post->id) ? 'Saved' : 'Save for later' }}">
                <svg class="w-8 h-8" fill="{{ Auth::user()->hasBookmarked($post->id) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                </svg>
                <span class="bookmark-text font-semibold">{{ Auth::user()->hasBookmarked($post->id) ? 'Saved' : 'Save' }}</span>
            </button>
        </div>
        @endauth

        <div class="prose prose-invert text-gray-300">
            <p class="text-lg whitespace-pre-wrap">{{ $post->description }}</p>
        </div>

        @auth
        <div class="mt-12 border-t border-gray-700 pt-8">
            <h3 class="text-2xl font-bold mb-6">Comments</h3>
            <div class="bg-gray-900 rounded-lg p-6 mb-6">
                <form action="{{ route('comments.store', $post) }}" method="POST">
                    @csrf
                    <textarea name="content" rows="3" placeholder="Write a comment..." class="w-full bg-gray-800 text-white rounded-lg p-4 border border-gray-700 focus:border-blue-500 focus:outline-none" required></textarea>
                    <button type="submit" class="mt-3 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Post Comment
                    </button>
                </form>
            </div>

            <div class="space-y-4">
                @forelse($post->allComments()->latest()->get() as $comment)
                    <div class="bg-gray-900 rounded-lg p-4 hover:bg-gray-800 transition-colors">
                        <div class="flex items-start space-x-3">
                            <a href="{{ $comment->user->username ? route('profile.show', $comment->user->username) : '#' }}" class="flex-shrink-0">
                                @if($comment->user->avatar)
                                    <img src="{{ asset('storage/' . $comment->user->avatar) }}" class="w-10 h-10 rounded-full hover:ring-2 hover:ring-purple-500 transition">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-purple-500 to-blue-500 flex items-center justify-center text-white font-bold hover:ring-2 hover:ring-purple-500 transition">
                                        {{ substr($comment->user->name, 0, 1) }}
                                    </div>
                                @endif
                            </a>
                            
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between mb-1">
                                    <div class="flex items-center flex-wrap gap-2">
                                        <a href="{{ $comment->user->username ? route('profile.show', $comment->user->username) : '#' }}" class="font-semibold text-white hover:text-purple-400 transition">
                                            {{ $comment->user->name }}
                                        </a>
                                        @if($comment->user->username)
                                            <a href="{{ route('profile.show', $comment->user->username) }}" class="text-gray-500 text-sm hover:text-purple-400">
                                                {{ '@' . $comment->user->username }}
                                            </a>
                                        @endif
                                        <span class="text-gray-500 text-sm">•</span>
                                        <span class="text-gray-500 text-sm">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    
                                    @if($comment->user->id !== Auth::id() && $comment->user->username)
                                        <button 
                                            data-user-id="{{ $comment->user->id }}"
                                            data-following="{{ Auth::user()->isFollowing($comment->user->id) ? 'true' : 'false' }}"
                                            class="follow-btn-comment flex-shrink-0 px-4 py-1.5 rounded-lg font-semibold text-sm transition-all duration-300 {{ Auth::user()->isFollowing($comment->user->id) ? 'bg-gray-700 text-gray-300 hover:bg-gray-600' : 'bg-gradient-to-r from-purple-600 to-pink-600 text-white hover:from-purple-700 hover:to-pink-700' }}">
                                            <span class="follow-text">{{ Auth::user()->isFollowing($comment->user->id) ? 'Following' : 'Follow' }}</span>
                                        </button>
                                    @endif
                                </div>
                                <p class="text-gray-300 break-words">{{ $comment->content }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">No comments yet. Be the first to comment!</p>
                @endforelse
            </div>
        </div>
        @else
        <div class="mt-12 border-t border-gray-700 pt-8 text-center">
            <p class="text-gray-400 mb-4">Please <a href="{{ route('login') }}" class="text-blue-400 hover:text-blue-300">log in</a> to like and comment on this post.</p>
        </div>
        @endauth
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

        document.querySelectorAll('.follow-btn-comment').forEach(button => {
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
                            this.className = 'follow-btn-comment ml-4 px-4 py-1.5 rounded-lg font-semibold text-sm transition-all duration-300 bg-gray-700 text-gray-300 hover:bg-gray-600';
                            followText.textContent = 'Following';
                        } else {
                            this.className = 'follow-btn-comment ml-4 px-4 py-1.5 rounded-lg font-semibold text-sm transition-all duration-300 bg-gradient-to-r from-purple-600 to-pink-600 text-white hover:from-purple-700 hover:to-pink-700';
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
                const svg = button.querySelector('svg');
                const text = button.querySelector('.bookmark-text');
                
                if (!isBookmarked) {
                    svg.setAttribute('fill', 'currentColor');
                    button.classList.remove('text-gray-400');
                    button.classList.add('text-yellow-500');
                    button.dataset.bookmarked = 'true';
                    button.title = 'Saved';
                    if (text) text.textContent = 'Saved';
                } else {
                    svg.setAttribute('fill', 'none');
                    button.classList.remove('text-yellow-500');
                    button.classList.add('text-gray-400');
                    button.dataset.bookmarked = 'false';
                    button.title = 'Save for later';
                    if (text) text.textContent = 'Save';
                }
            } catch (error) {
                console.error('Error toggling bookmark:', error);
            }
        }
        @endauth
    </script>
</body>
</html>