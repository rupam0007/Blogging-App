<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function welcome()
    {
        $posts = Post::with(['user:id,name,username,avatar', 'reactions', 'comments', 'tags'])
            ->where('status', 'published')
            ->latest()
            ->take(3)
            ->get();
        
        return view('welcome', compact('posts'));
    }
    
    public function index()
    {
        $posts = Post::with(['user:id,name,username,avatar', 'reactions', 'comments.user', 'tags'])
                    ->withCount(['reactions', 'comments'])
                    ->where('status', 'published')
                    ->latest()
                    ->get();
        
        return view('posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        if ($post->status !== 'published' && (!Auth::check() || Auth::id() !== $post->user_id)) {
            abort(404);
        }
        
        $post->load([
            'user:id,name,username,avatar,bio',
            'reactions.user:id,name,username,avatar',
            'comments.user:id,name,username,avatar',
            'comments.replies.user:id,name,username,avatar',
            'tags'
        ]);
        
        return view('posts.show', compact('post'));
    }

    public function dashboard()
    {
        $user = Auth::user();
        
        $followingIds = $user->following()->pluck('following_id')->toArray();
        $followingIds[] = $user->id;
        
        $feed = Post::with(['user', 'reactions', 'allComments'])
            ->whereIn('user_id', $followingIds)
            ->where('status', 'published')
            ->latest()
            ->paginate(10);
        
        $stories = Story::with('user')
            ->whereIn('user_id', $followingIds)
            ->active()
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('user_id');
        
        $userPosts = $user->posts()->latest()->get();
        $stats = [
            'total' => $userPosts->count(),
            'published' => $userPosts->where('status', 'published')->count(),
            'draft' => $userPosts->where('status', 'draft')->count(),
        ];
        
        $myPosts = $user->posts()
            ->withCount(['reactions', 'allComments'])
            ->latest()
            ->paginate(5, ['*'], 'my_posts');
        
        $trendingPosts = Post::withCount('reactions')
            ->where('status', 'published')
            ->where('created_at', '>=', now()->subDays(7))
            ->orderBy('reactions_count', 'desc')
            ->take(5)
            ->get();

        // Suggested users to follow
        $suggestedUsers = \App\Models\User::whereNotIn('id', $followingIds)
            ->where('id', '!=', $user->id)
            ->withCount(['posts', 'followers'])
            ->having('posts_count', '>', 0)
            ->orderBy('followers_count', 'desc')
            ->take(5)
            ->get();

        return view('posts.dashboard', compact('feed', 'stories', 'stats', 'trendingPosts', 'myPosts', 'suggestedUsers'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'blog_media' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,ogg,qt|max:51200',
            'status' => 'required|in:draft,published',
        ]);

        $imagePath = null;
        $videoPath = null;
        
        if ($request->hasFile('blog_media')) {
            $file = $request->file('blog_media');
            $mimeType = $file->getMimeType();
            
            if (str_starts_with($mimeType, 'image/')) {
                $imagePath = $file->store('blog_images', 'public');
            } elseif (str_starts_with($mimeType, 'video/')) {
                $videoPath = $file->store('blog_videos', 'public');
            }
        }

        Post::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => $imagePath,
            'video_path' => $videoPath,
            'status' => $request->status ?? 'published',
        ]);

        $message = $request->status === 'draft' 
            ? 'Post saved as draft successfully!' 
            : 'Post published successfully!';

        return redirect()->route('dashboard')->with('success', $message);
    }

    public function edit(Post $post)
    {
        if (Auth::id() !== $post->user_id) {
            abort(403);
        }
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        if (Auth::id() !== $post->user_id) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'blog_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published',
        ]);

        $imagePath = $post->image_path;
        
        if ($request->hasFile('blog_image')) {
            if ($post->image_path) {
                Storage::disk('public')->delete($post->image_path);
            }
            $imagePath = $request->file('blog_image')->store('blog_images', 'public');
        }

        $post->update([
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => $imagePath,
            'status' => $request->status,
        ]);

        return redirect()->route('dashboard')->with('success', 'Blog post updated successfully!');
    }

    public function destroy(Post $post)
    {
        if (Auth::id() !== $post->user_id) {
            abort(403);
        }

        if ($post->image_path) {
            Storage::disk('public')->delete($post->image_path);
        }

        $post->delete();
        
        return redirect()->route('dashboard')->with('success', 'Blog post deleted successfully!');
    }
}