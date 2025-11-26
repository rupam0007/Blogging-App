@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-4xl font-bold text-white mb-2">Stories</h1>
                <p class="text-gray-400">Share moments that disappear in 24 hours</p>
            </div>
            <a href="{{ route('stories.create') }}" class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white px-6 py-3 rounded-lg font-medium transition-all flex items-center">
                <span class="material-symbols-outlined mr-2" style="font-size: 20px;">add_circle</span>
                Create Story
            </a>
        </div>

        @if($stories->count() > 0)
            <!-- Stories Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($stories as $userId => $userStories)
                    @php
                        $firstStory = $userStories->first();
                        $user = $firstStory->user;
                        $storyCount = $userStories->count();
                    @endphp
                    
                    <a href="{{ route('stories.show', $userId) }}" class="group">
                        <div class="relative">
                            <!-- Story Image/Video Container -->
                            <div class="relative aspect-[9/16] rounded-xl overflow-hidden">
                                @if($firstStory->media_type === 'image')
                                    <img src="{{ Storage::url($firstStory->media_path) }}" alt="Story" class="w-full h-full object-cover">
                                @else
                                    <video class="w-full h-full object-cover">
                                        <source src="{{ Storage::url($firstStory->media_path) }}" type="video/mp4">
                                    </video>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-white opacity-80" style="font-size: 48px; font-variation-settings: 'FILL' 1;">play_circle</span>
                                    </div>
                                @endif

                                <!-- Gradient Overlay -->
                                <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-black opacity-60"></div>

                                <!-- User Info Overlay -->
                                <div class="absolute bottom-0 left-0 right-0 p-3">
                                    <p class="text-white text-sm font-medium truncate">{{ $user->name }}</p>
                                    @if($storyCount > 1)
                                        <p class="text-gray-300 text-xs">{{ $storyCount }} stories</p>
                                    @endif
                                </div>

                                <!-- Ring Gradient (Multiple Stories) -->
                                <div class="absolute inset-0 rounded-xl ring-4 {{ $storyCount > 1 ? 'ring-purple-500' : 'ring-pink-500' }} ring-opacity-100 group-hover:ring-opacity-75 transition-all"></div>
                            </div>

                            <!-- User Avatar -->
                            <div class="absolute -bottom-3 left-1/2 transform -translate-x-1/2">
                                @if($user->avatar)
                                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full border-4 border-gray-900">
                                @else
                                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full border-4 border-gray-900 flex items-center justify-center text-white text-xs font-bold">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-gray-800 rounded-xl border border-gray-700 p-12 text-center">
                <span class="material-symbols-outlined text-gray-600 mx-auto mb-4" style="font-size: 80px;">video_camera_front</span>
                <h3 class="text-white text-xl font-semibold mb-2">No active stories</h3>
                <p class="text-gray-400 mb-6">Be the first to share a story</p>
                <a href="{{ route('stories.create') }}" class="inline-block bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white px-6 py-3 rounded-lg font-medium transition-all">
                    Create Your First Story
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
