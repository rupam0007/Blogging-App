<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function index()
    {
        $bookmarks = Auth::user()->bookmarkedPosts()
            ->with(['user', 'reactions', 'comments'])
            ->latest('pivot_created_at')
            ->paginate(12);
            
        return view('bookmarks.index', compact('bookmarks'));
    }

    public function store(Post $post)
    {
        if (!Auth::user()->hasBookmarked($post->id)) {
            Auth::user()->bookmarkedPosts()->attach($post->id);
            
            if (request()->ajax()) {
                return response()->json(['success' => true, 'message' => 'Post bookmarked!']);
            }
            return back()->with('success', 'Post bookmarked!');
        }
        
        return back()->with('info', 'Already bookmarked.');
    }

    public function destroy(Post $post)
    {
        Auth::user()->bookmarkedPosts()->detach($post->id);
        
        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Bookmark removed!']);
        }
        return back()->with('success', 'Bookmark removed!');
    }
}