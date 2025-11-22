<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Admin dashboard with analytics
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_posts' => Post::count(),
            'published_posts' => Post::where('status', 'published')->count(),
            'draft_posts' => Post::where('status', 'draft')->count(),
            'total_comments' => Comment::count(),
            'active_stories' => Story::active()->count(),
            'new_users_today' => User::whereDate('created_at', Carbon::today())->count(),
            'new_posts_today' => Post::whereDate('created_at', Carbon::today())->count(),
        ];

        // Most liked posts
        $topPosts = Post::withCount('reactions')
            ->orderBy('reactions_count', 'desc')
            ->limit(10)
            ->get();

        // Most active users
        $topUsers = User::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit(10)
            ->get();

        // Recent signups
        $recentUsers = User::latest()->limit(10)->get();

        return view('admin.dashboard', compact('stats', 'topPosts', 'topUsers', 'recentUsers'));
    }

    /**
     * Manage users
     */
    public function users(Request $request)
    {
        $users = User::withCount('posts', 'followers', 'following')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%")
                      ->orWhere('username', 'LIKE', "%{$search}%");
            })
            ->when($request->role, function ($query, $role) {
                $query->where('role', $role);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Ban/suspend a user
     */
    public function banUser(User $user)
    {
        // You can add a 'status' field to users table
        // $user->update(['status' => 'banned']);
        
        return back()->with('success', 'User has been banned.');
    }

    /**
     * Manage posts
     */
    public function posts(Request $request)
    {
        $posts = Post::with('user')
            ->withCount('reactions', 'allComments')
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Delete a post
     */
    public function deletePost(Post $post)
    {
        $post->delete();
        return back()->with('success', 'Post deleted successfully!');
    }

    /**
     * Manage comments
     */
    public function comments(Request $request)
    {
        $comments = Comment::with('user', 'post')
            ->latest()
            ->paginate(20);

        return view('admin.comments.index', compact('comments'));
    }

    /**
     * Delete a comment
     */
    public function deleteComment(Comment $comment)
    {
        $comment->delete();
        return back()->with('success', 'Comment deleted successfully!');
    }

    /**
     * Make user admin
     */
    public function makeAdmin(User $user)
    {
        $user->update(['role' => 'admin']);
        return back()->with('success', $user->name . ' is now an admin!');
    }

    /**
     * Remove admin role
     */
    public function removeAdmin(User $user)
    {
        $user->update(['role' => 'user']);
        return back()->with('success', 'Admin role removed from ' . $user->name);
    }
}
