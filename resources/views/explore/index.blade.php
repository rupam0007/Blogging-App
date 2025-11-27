@extends('layouts.app')

@section('title', 'Explore')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Explore</h1>
        <p class="text-gray-600 dark:text-gray-400">Discover trending posts and popular creators</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Main Content -->
        <div class="flex-1">
            <!-- Filter Tabs -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm mb-6">
                <div class="flex border-b border-gray-200 dark:border-gray-700 overflow-x-auto">
                    <a href="{{ route('explore', ['filter' => 'trending']) }}" 
                       class="px-6 py-4 text-sm font-medium whitespace-nowrap {{ $filter === 'trending' ? 'text-blue-600 dark:text-blue-500 border-b-2 border-blue-600 dark:border-blue-500' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                        <span class="material-symbols-outlined text-sm align-middle mr-1">trending_up</span>
                        Trending
                    </a>
                    <a href="{{ route('explore', ['filter' => 'popular']) }}" 
                       class="px-6 py-4 text-sm font-medium whitespace-nowrap {{ $filter === 'popular' ? 'text-blue-600 dark:text-blue-500 border-b-2 border-blue-600 dark:border-blue-500' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                        <span class="material-symbols-outlined text-sm align-middle mr-1">local_fire_department</span>
                        Popular
                    </a>
                    <a href="{{ route('explore', ['filter' => 'latest']) }}" 
                       class="px-6 py-4 text-sm font-medium whitespace-nowrap {{ $filter === 'latest' ? 'text-blue-600 dark:text-blue-500 border-b-2 border-blue-600 dark:border-blue-500' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                        <span class="material-symbols-outlined text-sm align-middle mr-1">schedule</span>
                        Latest
                    </a>
                    @auth
                    <a href="{{ route('explore', ['filter' => 'following']) }}" 
                       class="px-6 py-4 text-sm font-medium whitespace-nowrap {{ $filter === 'following' ? 'text-blue-600 dark:text-blue-500 border-b-2 border-blue-600 dark:border-blue-500' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                        <span class="material-symbols-outlined text-sm align-middle mr-1">people</span>
                        Following
                    </a>
                    @endauth
                </div>
            </div>

            <!-- Posts Grid -->
            @if($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    @foreach($posts as $post)
                        <article class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-200">
                            <!-- Post Image/Video -->
                            @if($post->image_path || $post->video_path)
                                <a href="{{ route('posts.show', $post->id) }}" class="block aspect-video relative overflow-hidden bg-gray-100 dark:bg-gray-700">
                                    @if($post->image_path)
                                        <img src="{{ asset('storage/' . $post->image_path) }}" 
                                             alt="{{ $post->title }}"
                                             class="w-full h-full object-cover">
                                    @elseif($post->video_path)
                                        <video class="w-full h-full object-cover">
                                            <source src="{{ asset('storage/' . $post->video_path) }}" type="video/mp4">
                                        </video>
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <span class="material-symbols-outlined text-white text-6xl" style="font-variation-settings: 'FILL' 1;">
                                                play_circle
                                            </span>
                                        </div>
                                    @endif
                                </a>
                            @else
                                <a href="{{ route('posts.show', $post->id) }}" class="block aspect-video relative overflow-hidden bg-gradient-to-br from-blue-500 to-purple-600">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-white text-6xl">article</span>
                                    </div>
                                </a>
                            @endif

                            <!-- Post Content -->
                            <div class="p-4">
                                <!-- Title -->
                                <a href="{{ route('posts.show', $post->id) }}" class="block">
                                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2 hover:text-blue-600 dark:hover:text-blue-500">
                                        {{ $post->title }}
                                    </h3>
                                </a>

                                <!-- Author -->
                                <div class="flex items-center mb-3">
                                    <a href="{{ route('profile.show', $post->user->id) }}" class="flex items-center group">
                                        @if($post->user->avatar)
                                            <img src="{{ asset('storage/' . $post->user->avatar) }}" 
                                                 alt="{{ $post->user->name }}"
                                                 class="w-8 h-8 rounded-full mr-2">
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center mr-2">
                                                <span class="text-white text-xs font-semibold">
                                                    {{ substr($post->user->name, 0, 2) }}
                                                </span>
                                            </div>
                                        @endif
                                        <span class="text-sm text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white">
                                            {{ $post->user->name }}
                                        </span>
                                    </a>
                                </div>

                                <!-- Stats -->
                                <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                                    <div class="flex items-center">
                                        <span class="material-symbols-outlined text-base mr-1">favorite</span>
                                        {{ $post->reactions->count() }}
                                    </div>
                                    <div class="flex items-center">
                                        <span class="material-symbols-outlined text-base mr-1">comment</span>
                                        {{ $post->comments->count() }}
                                    </div>
                                </div>

                                <!-- Tags -->
                                @if($post->tags->count() > 0)
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        @foreach($post->tags->take(3) as $tag)
                                            <a href="{{ route('tags.show', $tag->slug) }}" 
                                               class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 hover:bg-blue-200 dark:hover:bg-blue-800">
                                                {{ $tag->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $posts->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-12 text-center">
                    <span class="material-symbols-outlined text-gray-400 dark:text-gray-600 text-6xl mb-4">search_off</span>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No posts found</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        @if($filter === 'following')
                            Start following creators to see their posts here
                        @else
                            Try changing the filter or check back later
                        @endif
                    </p>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:w-80 space-y-6">
            <!-- Popular Tags -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h3 class="flex items-center text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    <span class="material-symbols-outlined text-xl mr-2">tag</span>
                    Popular Tags
                </h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($popularTags as $tag)
                        <a href="{{ route('tags.show', $tag->slug) }}" 
                           class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600">
                            {{ $tag->name }}
                            <span class="ml-1.5 text-xs text-gray-500 dark:text-gray-400">{{ $tag->posts_count }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            @auth
            <!-- Suggested Users -->
            @if($suggestedUsers->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h3 class="flex items-center text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    <span class="material-symbols-outlined text-xl mr-2">person_add</span>
                    Suggested for You
                </h3>
                <div class="space-y-4">
                    @foreach($suggestedUsers as $user)
                        <div class="flex items-center justify-between">
                            <a href="{{ route('profile.show', $user->username ?? $user->id) }}" class="flex items-center flex-1 group">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" 
                                         alt="{{ $user->name }}"
                                         class="w-10 h-10 rounded-full mr-3">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-blue-600 flex items-center justify-center mr-3">
                                        <span class="text-white text-sm font-semibold">
                                            {{ substr($user->name, 0, 1) }}
                                        </span>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate group-hover:text-blue-600 dark:group-hover:text-blue-500">
                                        {{ $user->name }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $user->followers_count }} followers
                                    </p>
                                </div>
                            </a>
                            <form action="{{ route('users.follow', $user) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-1.5 text-xs font-semibold text-white bg-gradient-to-r from-purple-600 to-blue-600 rounded-full hover:from-purple-700 hover:to-blue-700 transition">
                                    Follow
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
            @endauth

            <!-- Top Creators -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h3 class="flex items-center text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    <span class="material-symbols-outlined text-xl mr-2">person_star</span>
                    Top Creators
                </h3>
                <div class="space-y-3">
                    @foreach($topCreators as $creator)
                        <a href="{{ route('profile.show', $creator->id) }}" class="flex items-center group">
                            @if($creator->avatar)
                                <img src="{{ asset('storage/' . $creator->avatar) }}" 
                                     alt="{{ $creator->name }}"
                                     class="w-10 h-10 rounded-full mr-3">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center mr-3">
                                    <span class="text-white text-sm font-semibold">
                                        {{ substr($creator->name, 0, 2) }}
                                    </span>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate group-hover:text-blue-600 dark:group-hover:text-blue-500">
                                    {{ $creator->name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $creator->posts_count }} {{ Str::plural('post', $creator->posts_count) }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
