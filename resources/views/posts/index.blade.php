@extends('layouts.app')

@section('title', 'Latest Blogs - Discover Amazing Content')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-950 dark:to-gray-900 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Hero Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-xl">
                    <span class="material-symbols-outlined text-white" style="font-size: 36px;">article</span>
                </div>
            </div>
            <h1 class="text-5xl font-extrabold text-gray-900 dark:text-white mb-4 tracking-tight">
                Latest <span class="bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Blogs</span>
            </h1>
            <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                Discover amazing stories and insights from our community of writers
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-center mb-12 gap-4">
            @auth
                <a href="{{ route('dashboard') }}" class="inline-flex items-center space-x-2 px-6 py-3 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-xl border-2 border-gray-200 dark:border-gray-700 hover:border-purple-500 dark:hover:border-purple-500 transition-all shadow-sm hover:shadow-md font-semibold">
                    <span class="material-symbols-outlined" style="font-size: 20px;">dashboard</span>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('posts.create') }}" class="inline-flex items-center space-x-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white rounded-xl transition-all shadow-md hover:shadow-lg font-semibold">
                    <span class="material-symbols-outlined" style="font-size: 20px;">add_circle</span>
                    <span>Create Post</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="inline-flex items-center space-x-2 px-8 py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white rounded-xl transition-all shadow-md hover:shadow-lg font-semibold">
                    <span class="material-symbols-outlined" style="font-size: 20px;">login</span>
                    <span>Log In to Post</span>
                </a>
            @endauth
        </div>

        <!-- Blog Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($posts as $post)
                <article class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg hover:shadow-2xl overflow-hidden border border-gray-200 dark:border-gray-800 flex flex-col transition-all duration-300 hover:-translate-y-1 group">
                    <!-- Media Section -->
                    @if ($post->image_path)
                        <div class="relative h-56 overflow-hidden cursor-pointer" onclick="openImageModal('{{ asset('storage/' . $post->image_path) }}', '{{ $post->title }}')">
                            <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                                    <span class="material-symbols-outlined text-white" style="font-size: 32px;">zoom_in</span>
                                </div>
                            </div>
                        </div>
                    @elseif ($post->video_path)
                        <div class="relative h-56">
                            <video controls class="w-full h-full object-cover">
                                <source src="{{ asset('storage/' . $post->video_path) }}" type="video/mp4"> 
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    @else
                        <div class="h-56 bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                            <span class="material-symbols-outlined text-white/30" style="font-size: 80px;">article</span>
                        </div>
                    @endif
                    
                    <!-- Content Section -->
                    <div class="p-6 flex flex-col flex-grow">
                        <h2 class="text-xl font-bold mb-3 text-gray-900 dark:text-white line-clamp-2 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                            <a href="{{ route('blogs.show', $post) }}">{{ $post->title }}</a>
                        </h2>
                        
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 flex-grow line-clamp-3">{{ Str::limit($post->description, 120) }}</p>
                        
                        <!-- Stats & Actions -->
                        @auth
                        <div class="flex items-center space-x-4 mb-4 pb-4 border-b border-gray-200 dark:border-gray-800">
                            <button onclick="toggleReaction({{ $post->id }}, this)" class="flex items-center space-x-1.5 {{ $post->hasUserReacted(Auth::id()) ? 'text-red-500' : 'text-gray-500 dark:text-gray-400' }} hover:text-red-500 transition-colors reaction-btn group/heart" data-reacted="{{ $post->hasUserReacted(Auth::id()) ? 'true' : 'false' }}">
                                <span class="material-symbols-outlined group-hover/heart:scale-110 transition-transform" style="font-size: 20px; font-variation-settings: 'FILL' {{ $post->hasUserReacted(Auth::id()) ? '1' : '0' }};">favorite</span>
                                <span class="text-sm font-medium reaction-count">{{ $post->reactions()->count() }}</span>
                            </button>

                            <a href="{{ route('blogs.show', $post) }}" class="flex items-center space-x-1.5 text-gray-500 dark:text-gray-400 hover:text-blue-500 transition-colors group/comment">
                                <span class="material-symbols-outlined group-hover/comment:scale-110 transition-transform" style="font-size: 20px;">comment</span>
                                <span class="text-sm font-medium">{{ $post->allComments()->count() }}</span>
                            </a>

                            <button onclick="toggleBookmark({{ $post->id }}, this)" class="flex items-center space-x-1.5 {{ Auth::user()->hasBookmarked($post->id) ? 'text-yellow-500' : 'text-gray-500 dark:text-gray-400' }} hover:text-yellow-500 transition-colors bookmark-btn ml-auto group/bookmark" data-bookmarked="{{ Auth::user()->hasBookmarked($post->id) ? 'true' : 'false' }}" title="{{ Auth::user()->hasBookmarked($post->id) ? 'Remove from bookmarks' : 'Save to bookmarks' }}">
                                <span class="material-symbols-outlined group-hover/bookmark:scale-110 transition-transform" style="font-size: 20px; font-variation-settings: 'FILL' {{ Auth::user()->hasBookmarked($post->id) ? '1' : '0' }};">bookmark</span>
                            </button>
                        </div>
                        @endauth
                        
                        <!-- Read More Link -->
                        <a href="{{ route('blogs.show', $post) }}" class="inline-flex items-center space-x-2 text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 font-semibold text-sm mb-4 group/link">
                            <span>Read More</span>
                            <span class="material-symbols-outlined group-hover/link:translate-x-1 transition-transform" style="font-size: 16px;">arrow_forward</span>
                        </a>
                        
                        <!-- Author Section -->
                        <div class="mt-auto pt-4 border-t border-gray-200 dark:border-gray-800">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center space-x-2 min-w-0">
                                    @if($post->user->avatar)
                                        <img src="{{ asset('storage/' . $post->user->avatar) }}" alt="{{ $post->user->name }}" class="w-8 h-8 rounded-full ring-2 ring-purple-500/20">
                                    @else
                                        <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                            {{ strtoupper(substr($post->user->name ?? 'G', 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-medium text-gray-900 dark:text-white truncate">
                                            <a href="{{ $post->user->username ? route('profile.show', $post->user->username) : '#' }}" class="hover:text-purple-600 dark:hover:text-purple-400">{{ $post->user->name ?? 'Guest' }}</a>
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-500">{{ $post->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                                @auth
                                    @if($post->user->id !== Auth::id() && $post->user->username)
                                        <button 
                                            data-user-id="{{ $post->user->id }}"
                                            data-following="{{ Auth::user()->isFollowing($post->user->id) ? 'true' : 'false' }}"
                                            class="follow-btn-blog px-3 py-1.5 rounded-lg font-semibold text-xs transition-all duration-300 whitespace-nowrap {{ Auth::user()->isFollowing($post->user->id) ? 'bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-700' : 'bg-gradient-to-r from-purple-600 to-pink-600 text-white hover:from-purple-700 hover:to-pink-700 shadow-sm' }}">
                                            <span class="follow-text">{{ Auth::user()->isFollowing($post->user->id) ? 'Following' : 'Follow' }}</span>
                                        </button>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full">
                    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-16 text-center">
                        <div class="w-24 h-24 bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/20 dark:to-pink-900/20 rounded-full flex items-center justify-center mx-auto mb-6">
                            <span class="material-symbols-outlined text-purple-500" style="font-size: 48px;">article</span>
                        </div>
                        <h3 class="text-gray-900 dark:text-white text-2xl font-bold mb-3">No Posts Yet</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">Be the first to share your story and inspire the community!</p>
                        @auth
                        <a href="{{ route('posts.create') }}" class="inline-flex items-center space-x-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white px-6 py-3 rounded-lg font-semibold transition-all shadow-md hover:shadow-lg">
                            <span class="material-symbols-outlined" style="font-size: 20px;">add_circle</span>
                            <span>Create First Post</span>
                        </a>
                        @endauth
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black/95 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4" onclick="closeImageModal()">
    <button onclick="closeImageModal()" class="absolute top-6 right-6 text-white hover:text-gray-300 transition-all hover:rotate-90 duration-300">
        <span class="material-symbols-outlined" style="font-size: 40px;">close</span>
    </button>
    <div class="max-w-7xl max-h-full" onclick="event.stopPropagation()">
        <img id="modalImage" src="" alt="" class="max-w-full max-h-[90vh] object-contain rounded-2xl shadow-2xl">
        <p id="modalCaption" class="text-white text-center mt-6 text-xl font-semibold"></p>
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
                const icon = button.querySelector('.material-symbols-outlined');
                
                if (!isReacted) {
                    icon.style.fontVariationSettings = "'FILL' 1";
                    button.classList.remove('text-gray-400', 'dark:text-gray-400');
                    button.classList.add('text-red-500');
                    button.dataset.reacted = 'true';
                } else {
                    icon.style.fontVariationSettings = "'FILL' 0";
                    button.classList.remove('text-red-500');
                    button.classList.add('text-gray-400', 'dark:text-gray-400');
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
                const icon = button.querySelector('.material-symbols-outlined');
                
                if (!isBookmarked) {
                    icon.style.fontVariationSettings = "'FILL' 1";
                    button.classList.remove('text-gray-400', 'dark:text-gray-400');
                    button.classList.add('text-yellow-500');
                    button.dataset.bookmarked = 'true';
                    button.title = 'Remove from bookmarks';
                } else {
                    icon.style.fontVariationSettings = "'FILL' 0";
                    button.classList.remove('text-yellow-500');
                    button.classList.add('text-gray-400', 'dark:text-gray-400');
                    button.dataset.bookmarked = 'false';
                    button.title = 'Save to bookmarks';
                }
            } catch (error) {
                console.error('Error toggling bookmark:', error);
            }
        }
        @endauth
    </script>
@endsection