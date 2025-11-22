@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-white mb-2">Admin Dashboard</h1>
            <p class="text-gray-400">Manage your Smart Blog platform</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Users -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 shadow-lg transform hover:scale-105 transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Users</p>
                        <p class="text-white text-3xl font-bold mt-2">{{ number_format($stats['total_users']) }}</p>
                    </div>
                    <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <p class="text-blue-100 text-xs mt-4">+{{ $stats['new_users_today'] }} today</p>
            </div>

            <!-- Total Posts -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 shadow-lg transform hover:scale-105 transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Total Posts</p>
                        <p class="text-white text-3xl font-bold mt-2">{{ number_format($stats['total_posts']) }}</p>
                    </div>
                    <svg class="w-12 h-12 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                </div>
                <p class="text-purple-100 text-xs mt-4">{{ $stats['published_posts'] }} published, {{ $stats['draft_posts'] }} drafts</p>
            </div>

            <!-- Total Comments -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 shadow-lg transform hover:scale-105 transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Total Comments</p>
                        <p class="text-white text-3xl font-bold mt-2">{{ number_format($stats['total_comments']) }}</p>
                    </div>
                    <svg class="w-12 h-12 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
            </div>

            <!-- Active Stories -->
            <div class="bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl p-6 shadow-lg transform hover:scale-105 transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-pink-100 text-sm font-medium">Active Stories</p>
                        <p class="text-white text-3xl font-bold mt-2">{{ number_format($stats['active_stories']) }}</p>
                    </div>
                    <svg class="w-12 h-12 text-pink-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <p class="text-pink-100 text-xs mt-4">Expires in 24 hours</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('admin.users') }}" class="bg-gray-800 hover:bg-gray-700 rounded-xl p-6 border border-gray-700 transition-all">
                <h3 class="text-white font-semibold text-lg mb-2">Manage Users</h3>
                <p class="text-gray-400 text-sm">View, ban, or promote users</p>
            </a>
            <a href="{{ route('admin.posts') }}" class="bg-gray-800 hover:bg-gray-700 rounded-xl p-6 border border-gray-700 transition-all">
                <h3 class="text-white font-semibold text-lg mb-2">Manage Posts</h3>
                <p class="text-gray-400 text-sm">Moderate published content</p>
            </a>
            <a href="{{ route('admin.comments') }}" class="bg-gray-800 hover:bg-gray-700 rounded-xl p-6 border border-gray-700 transition-all">
                <h3 class="text-white font-semibold text-lg mb-2">Manage Comments</h3>
                <p class="text-gray-400 text-sm">Review and moderate comments</p>
            </a>
        </div>

        <!-- Top Posts & Top Users -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top Posts -->
            <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-700">
                    <h3 class="text-white font-semibold text-lg">Top Posts (Most Reactions)</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($topPosts as $post)
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center text-white font-bold">
                                        {{ $post->reactions_count }}
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('blogs.show', $post) }}" class="text-white hover:text-purple-400 font-medium block truncate">
                                        {{ $post->title }}
                                    </a>
                                    <p class="text-gray-400 text-sm">by {{ $post->user->name }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-400 text-center py-8">No posts yet</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Top Users -->
            <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-700">
                    <h3 class="text-white font-semibold text-lg">Top Users (Most Posts)</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($topUsers as $user)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    @if($user->avatar)
                                        <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full">
                                    @else
                                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <a href="{{ route('profile.show', $user->username ?? $user->id) }}" class="text-white hover:text-purple-400 font-medium">
                                            {{ $user->name }}
                                        </a>
                                        <p class="text-gray-400 text-xs">@if($user->username){{ '@' . $user->username }}@endif</p>
                                    </div>
                                </div>
                                <span class="bg-purple-600 text-white px-3 py-1 rounded-full text-sm font-medium">
                                    {{ $user->posts_count }} posts
                                </span>
                            </div>
                        @empty
                            <p class="text-gray-400 text-center py-8">No users yet</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Users -->
        <div class="mt-6 bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-700">
                <h3 class="text-white font-semibold text-lg">Recent Signups</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse($recentUsers as $user)
                            <tr class="hover:bg-gray-750">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($user->avatar)
                                            <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-8 h-8 rounded-full">
                                        @else
                                            <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <span class="ml-3 text-white">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-400">{{ $user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $user->role === 'admin' ? 'bg-red-600 text-white' : 'bg-gray-700 text-gray-300' }}">
                                        {{ ucfirst($user->role ?? 'user') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-400">{{ $user->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-400">No recent users</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
