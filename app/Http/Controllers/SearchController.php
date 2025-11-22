<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Search for blogs, users, and tags
     */
    public function index(Request $request)
    {
        $query = $request->input('q');
        $type = $request->input('type', 'all'); // all, posts, users

        // Get total counts for all types
        $postsCount = 0;
        $usersCount = 0;

        if ($query) {
            $postsCount = Post::where('status', 'published')
                ->where(function ($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                      ->orWhere('description', 'LIKE', "%{$query}%");
                })
                ->count();
                
            $usersCount = User::where('name', 'LIKE', "%{$query}%")
                ->orWhere('username', 'LIKE', "%{$query}%")
                ->orWhere('bio', 'LIKE', "%{$query}%")
                ->count();
        }

        // Get paginated results based on type
        $posts = collect();
        $users = collect();

        if ($type === 'all' || $type === 'posts') {
            $posts = Post::where('status', 'published')
                ->where(function ($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                      ->orWhere('description', 'LIKE', "%{$query}%");
                })
                ->with('user', 'reactions', 'allComments')
                ->latest()
                ->paginate(12);
        }

        if ($type === 'all' || $type === 'users') {
            $users = User::where('name', 'LIKE', "%{$query}%")
                ->orWhere('username', 'LIKE', "%{$query}%")
                ->orWhere('bio', 'LIKE', "%{$query}%")
                ->withCount('posts', 'followers')
                ->paginate(12);
        }

        return view('search.results', compact('query', 'posts', 'users', 'type', 'postsCount', 'usersCount'));
    }

    /**
     * Autocomplete search suggestions
     */
    public function autocomplete(Request $request)
    {
        $query = $request->input('q');

        $suggestions = [
            'posts' => Post::where('status', 'published')
                ->where('title', 'LIKE', "%{$query}%")
                ->limit(5)
                ->pluck('title'),
            'users' => User::where('name', 'LIKE', "%{$query}%")
                ->orWhere('username', 'LIKE', "%{$query}%")
                ->limit(5)
                ->get(['id', 'name', 'username', 'avatar'])
        ];

        return response()->json($suggestions);
    }
}
