<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function follow(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot follow yourself.');
        }

        if (!Auth::user()->isFollowing($user->id)) {
            Auth::user()->following()->attach($user->id);

            Notification::create([
                'user_id' => $user->id,
                'from_user_id' => Auth::id(),
                'type' => 'follow',
                'notifiable_type' => User::class,
                'notifiable_id' => Auth::id(),
                'content' => Auth::user()->name . ' started following you',
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'You are now following ' . $user->name,
                    'followers_count' => $user->followers()->count()
                ]);
            }

            return back()->with('success', 'You are now following ' . $user->name);
        }

        return back()->with('info', 'You are already following this user.');
    }

    public function unfollow(User $user)
    {
        if (Auth::user()->isFollowing($user->id)) {
            Auth::user()->following()->detach($user->id);
            
            // No notification sent for unfollows (standard social media behavior)
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'You unfollowed ' . $user->name,
                    'followers_count' => $user->followers()->count()
                ]);
            }
            
            return back()->with('success', 'You unfollowed ' . $user->name);
        }

        if (request()->ajax()) {
            return response()->json(['success' => false, 'message' => 'You are not following this user.']);
        }

        return back()->with('info', 'You are not following this user.');
    }

    public function followers(Request $request, User $user)
    {
        $search = $request->input('search');
        
        $followers = $user->followers()
            ->withCount(['posts', 'followers', 'following'])
            ->when($search, function ($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('username', 'LIKE', "%{$search}%");
                });
            })
            ->paginate(20)
            ->withQueryString();
        
        return view('users.followers', compact('user', 'followers'));
    }

    public function following(Request $request, User $user)
    {
        $search = $request->input('search');
        
        $following = $user->following()
            ->withCount(['posts', 'followers', 'following'])
            ->when($search, function ($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('username', 'LIKE', "%{$search}%");
                });
            })
            ->paginate(20)
            ->withQueryString();
        
        return view('users.following', compact('user', 'following'));
    }
}