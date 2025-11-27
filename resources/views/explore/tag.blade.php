@extends('layouts.app')

@section('title', $tag->name . ' - Explore')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-8 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                    <span class="material-symbols-outlined text-3xl align-middle mr-2">tag</span>
                    {{ $tag->name }}
                </h1>
                <p class="text-gray-600 dark:text-gray-400">{{ $tag->description }}</p>
            </div>
            <a href="{{ route('explore') }}" 
               class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600">
                <span class="material-symbols-outlined text-base mr-2">arrow_back</span>
                Back to Explore
            </a>
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
                                @foreach($post->tags->take(3) as $postTag)
                                    <a href="{{ route('tags.show', $postTag->slug) }}" 
                                       class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $postTag->id === $tag->id ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 hover:bg-blue-200 dark:hover:bg-blue-800' }}">
                                        {{ $postTag->name }}
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
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                There are no posts with the tag "{{ $tag->name }}" yet.
            </p>
            <a href="{{ route('explore') }}" 
               class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                <span class="material-symbols-outlined text-base mr-2">explore</span>
                Explore Other Tags
            </a>
        </div>
    @endif
</div>
@endsection
