<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReactionController extends Controller
{
    public function reactToPost(Request $request, Post $post)
    {
        $request->validate(['type' => 'required|in:like,love,laugh,angry,sad,wow']);

        $reaction = $post->reactions()->where('user_id', Auth::id())->first();

        if ($reaction) {
            $reaction->update(['type' => $request->type]);
            $message = 'Reaction updated!';
        } else {
            $post->reactions()->create([
                'user_id' => Auth::id(),
                'type' => $request->type,
            ]);
            $message = 'Reaction added!';

            if ($post->user_id !== Auth::id()) {
                Notification::create([
                    'user_id' => $post->user_id,
                    'from_user_id' => Auth::id(),
                    'type' => 'reaction',
                    'notifiable_type' => Post::class,
                    'notifiable_id' => $post->id,
                    'content' => Auth::user()->name . ' reacted to your post',
                ]);
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'reaction_count' => $post->reactions()->count()
            ]);
        }

        return back()->with('success', $message);
    }

    public function unreactToPost(Post $post)
    {
        $post->reactions()->where('user_id', Auth::id())->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'reaction_count' => $post->reactions()->count()
            ]);
        }

        return back()->with('success', 'Reaction removed!');
    }
}