<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class StoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $followingIds = $user->following()->pluck('following_id')->toArray();
        $followingIds[] = $user->id;
        
        // Cache stories for 30 seconds to prevent excessive queries
        $cacheKey = 'stories_' . $user->id . '_' . md5(implode(',', $followingIds));
        $stories = cache()->remember($cacheKey, 30, function () use ($followingIds) {
            return Story::with('user', 'reactions')
                ->whereIn('user_id', $followingIds)
                ->active()
                ->orderBy('created_at', 'desc')
                ->get()
                ->groupBy('user_id');
        });
        
        return view('stories.index', compact('stories'));
    }

    public function create()
    {
        return view('stories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'media' => 'required|file|mimes:jpg,jpeg,png,gif,mp4,mov|max:20480',
            'caption' => 'nullable|string|max:500',
        ]);

        $mediaPath = $request->file('media')->store('stories', 'public');
        $mediaType = str_starts_with($request->file('media')->getMimeType(), 'video') ? 'video' : 'image';

        Story::create([
            'user_id' => Auth::id(),
            'media_path' => $mediaPath,
            'media_type' => $mediaType,
            'caption' => $request->caption,
            'expires_at' => Carbon::now()->addHours(24),
        ]);

        return redirect()->route('stories.index')->with('success', 'Story posted!');
    }

    public function show($userId)
    {
        $stories = Story::where('user_id', $userId)
            ->active()
            ->with('user', 'reactions')
            ->withCount('reactions')
            ->orderBy('created_at', 'desc')
            ->get();

        $stories->each(function ($story) {
            $story->user_reacted = Auth::check() && $story->reactions()
                ->where('user_id', Auth::id())
                ->exists();
        });

        if ($stories->isEmpty()) {
            return redirect()->route('stories.index')->with('error', 'No active stories.');
        }

        return view('stories.show', compact('stories'));
    }

    public function destroy(Story $story)
    {
        if ($story->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized.');
        }

        if (Storage::disk('public')->exists($story->media_path)) {
            Storage::disk('public')->delete($story->media_path);
        }

        $story->delete();
        return redirect()->route('stories.index')->with('success', 'Story deleted!');
    }

    public function react(Request $request, Story $story)
    {
        $request->validate(['type' => 'required|in:like,love,laugh,angry,sad,wow']);

        $reaction = $story->reactions()->where('user_id', Auth::id())->first();

        if ($reaction) {
            if ($reaction->type === $request->type) {
                $reaction->delete();
                $reacted = false;
            } else {
                $reaction->update(['type' => $request->type]);
                $reacted = true;
            }
        } else {
            $story->reactions()->create([
                'user_id' => Auth::id(),
                'type' => $request->type,
            ]);
            $reacted = true;
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'reacted' => $reacted,
                'reaction_count' => $story->reactions()->count()
            ]);
        }

        return back();
    }

    public function unreact(Story $story)
    {
        $story->reactions()->where('user_id', Auth::id())->delete();
        return back();
    }
}