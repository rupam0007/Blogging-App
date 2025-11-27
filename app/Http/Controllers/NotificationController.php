<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(20);
        return view('notifications.index', compact('notifications'));
    }

    public function unreadCount()
    {
        return response()->json([
            'count' => Auth::user()->unreadNotifications()->count()
        ]);
    }

    public function recent()
    {
        $notifications = Auth::user()->notifications()
            ->with('fromUser')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'data' => $notification->data,
                    'read_at' => $notification->read_at,
                    'created_at' => $notification->created_at->toISOString(),
                    'from_user' => $notification->fromUser ? [
                        'id' => $notification->fromUser->id,
                        'name' => $notification->fromUser->name,
                        'avatar' => $notification->fromUser->avatar,
                    ] : null,
                ];
            });

        return response()->json([
            'notifications' => $notifications
        ]);
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['success' => false], 403);
        }

        $notification->update(['read_at' => now()]);
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);
        return response()->json(['success' => true, 'message' => 'All notifications marked as read']);
    }

    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }
        
        $notification->delete();
        return back()->with('success', 'Notification removed');
    }
}