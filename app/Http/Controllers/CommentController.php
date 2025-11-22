<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = $post->allComments()->create([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'parent_id' => $request->parent_id,
        ]);

        if ($post->user_id !== Auth::id()) {
            Notification::create([
                'user_id' => $post->user_id,
                'from_user_id' => Auth::id(),
                'type' => 'comment',
                'notifiable_type' => Post::class,
                'notifiable_id' => $post->id,
                'content' => Auth::user()->name . ' commented on your post',
            ]);
        }

        if ($request->parent_id) {
            $parentComment = Comment::find($request->parent_id);
            if ($parentComment && $parentComment->user_id !== Auth::id()) {
                Notification::create([
                    'user_id' => $parentComment->user_id,
                    'from_user_id' => Auth::id(),
                    'type' => 'comment',
                    'notifiable_type' => Comment::class,
                    'notifiable_id' => $comment->id,
                    'content' => Auth::user()->name . ' replied to your comment',
                ]);
            }
        }

        $this->processMentions($request->content, $post, $comment);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Comment posted!',
                'comment' => $comment->load('user'),
                'comments_count' => $post->allComments()->count()
            ]);
        }

        return back()->with('success', 'Comment posted!');
    }

    public function update(Request $request, Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized.');
        }

        $request->validate(['content' => 'required|string|max:1000']);
        $comment->update(['content' => $request->content]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Comment updated!',
                'comment' => $comment->fresh()
            ]);
        }

        return back()->with('success', 'Comment updated!');
    }

    public function destroy(Comment $comment)
    {
        $canDelete = $comment->user_id === Auth::id();
        
        if (!$canDelete && Auth::user()->role === 'admin') {
            $canDelete = true;
        }
        
        if (!$canDelete) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
            }
            return back()->with('error', 'Unauthorized.');
        }

        $postId = $comment->post_id;
        $comment->delete();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Comment deleted!',
                'comments_count' => Post::find($postId)->allComments()->count()
            ]);
        }
        
        return back()->with('success', 'Comment deleted!');
    }

    private function processMentions($content, $post, $comment)
    {
        preg_match_all('/@(\w+)/', $content, $matches);
        $usernames = $matches[1];

        foreach ($usernames as $username) {
            $user = \App\Models\User::where('username', $username)->first();
            if ($user && $user->id !== Auth::id()) {
                Notification::create([
                    'user_id' => $user->id,
                    'from_user_id' => Auth::id(),
                    'type' => 'mention',
                    'notifiable_type' => Comment::class,
                    'notifiable_id' => $comment->id,
                    'content' => Auth::user()->name . ' mentioned you in a comment',
                ]);
            }
        }
    }
}
