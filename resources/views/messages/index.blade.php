@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-6">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-800">
        <!-- Header -->
        <div class="p-6 border-b border-gray-200 dark:border-gray-800">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center">
                    <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 mr-3 text-3xl">mail</span>
                    Messages
                </h1>
                <a href="{{ route('dashboard') }}" class="text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition">
                    <span class="material-symbols-outlined">close</span>
                </a>
            </div>
        </div>

        <!-- Conversations List -->
        <div class="divide-y divide-gray-200 dark:divide-gray-800">
            @forelse($conversations as $conversation)
                <a href="{{ route('messages.show', $conversation['user']) }}" 
                   class="flex items-center p-4 hover:bg-gray-50 dark:hover:bg-gray-800 transition group">
                    <!-- Avatar -->
                    <div class="relative flex-shrink-0">
                        @if($conversation['user']->avatar)
                            <img src="{{ asset('storage/' . $conversation['user']->avatar) }}" 
                                 alt="{{ $conversation['user']->name }}"
                                 class="w-14 h-14 rounded-full ring-2 ring-transparent group-hover:ring-purple-500 transition">
                        @else
                            <div class="w-14 h-14 rounded-full bg-gradient-to-br from-purple-500 to-blue-600 flex items-center justify-center text-white font-bold text-xl ring-2 ring-transparent group-hover:ring-purple-500 transition">
                                {{ substr($conversation['user']->name, 0, 1) }}
                            </div>
                        @endif
                        @if($conversation['unread_count'] > 0)
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center">
                                {{ $conversation['unread_count'] }}
                            </span>
                        @endif
                    </div>

                    <!-- Message Info -->
                    <div class="ml-4 flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white truncate group-hover:text-purple-600 dark:group-hover:text-purple-400 transition">
                                {{ $conversation['user']->name }}
                            </h3>
                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-2 flex-shrink-0">
                                {{ $conversation['latest_message']->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 truncate {{ $conversation['unread_count'] > 0 ? 'font-semibold' : '' }}">
                            @if($conversation['latest_message']->sender_id === Auth::id())
                                <span class="text-purple-600 dark:text-purple-400">You: </span>
                            @endif
                            @if($conversation['latest_message']->message)
                                {{ Str::limit($conversation['latest_message']->message, 50) }}
                            @elseif($conversation['latest_message']->file_type === 'image')
                                <span class="flex items-center">
                                    <span class="material-symbols-outlined text-sm mr-1">image</span>
                                    Photo
                                </span>
                            @elseif($conversation['latest_message']->file_type === 'video')
                                <span class="flex items-center">
                                    <span class="material-symbols-outlined text-sm mr-1">videocam</span>
                                    Video
                                </span>
                            @elseif($conversation['latest_message']->file_type === 'pdf')
                                <span class="flex items-center">
                                    <span class="material-symbols-outlined text-sm mr-1">description</span>
                                    PDF
                                </span>
                            @else
                                <span class="flex items-center">
                                    <span class="material-symbols-outlined text-sm mr-1">attach_file</span>
                                    File
                                </span>
                            @endif
                        </p>
                    </div>

                    <span class="material-symbols-outlined text-gray-400 ml-2">chevron_right</span>
                </a>
            @empty
                <div class="p-12 text-center">
                    <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-gray-700 mb-4 block">mail_outline</span>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No messages yet</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Start a conversation by visiting a user's profile</p>
                    <a href="{{ route('explore') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-blue-700 transition">
                        <span class="material-symbols-outlined mr-2">explore</span>
                        Explore Users
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
