<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    /**
     * Display user's bookmarked posts
     */
    public function index()
    {
        $bookmarkedPosts = Auth::user()->bookmarkedPosts()
            ->with(['user'])
            ->withCount(['reactions', 'allComments', 'bookmarks'])
            ->latest('bookmarks.created_at')
            ->paginate(12);

        return view('bookmarks.index', compact('bookmarkedPosts'));
    }

    /**
     * Bookmark a post
     */
    public function store(Post $post)
    {
        if (!Auth::user()->hasBookmarked($post->id)) {
            Bookmark::create([
                'user_id' => Auth::id(),
                'post_id' => $post->id,
            ]);

            if (request()->ajax()) {
                return response()->json(['success' => true, 'message' => 'Post bookmarked!']);
            }

            return back()->with('success', 'Post bookmarked!');
        }

        if (request()->ajax()) {
            return response()->json(['success' => false, 'message' => 'Already bookmarked']);
        }

        return back()->with('info', 'You have already bookmarked this post.');
    }

    /**
     * Remove bookmark
     */
    public function destroy(Post $post)
    {
        $deleted = Bookmark::where('user_id', Auth::id())
            ->where('post_id', $post->id)
            ->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Bookmark removed!']);
        }

        return back()->with('success', 'Bookmark removed!');
    }
}
