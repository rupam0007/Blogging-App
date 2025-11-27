<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    // Show all conversations
    public function index()
    {
        $userId = Auth::id();
        
        // Get unique conversations with latest message
        $conversations = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver'])
            ->latest()
            ->get()
            ->groupBy(function ($message) use ($userId) {
                return $message->sender_id === $userId 
                    ? $message->receiver_id 
                    : $message->sender_id;
            })
            ->map(function ($messages) use ($userId) {
                $latestMessage = $messages->first();
                $otherUserId = $latestMessage->sender_id === $userId 
                    ? $latestMessage->receiver_id 
                    : $latestMessage->sender_id;
                
                return [
                    'user' => User::find($otherUserId),
                    'latest_message' => $latestMessage,
                    'unread_count' => $messages->where('receiver_id', $userId)
                        ->whereNull('read_at')
                        ->count()
                ];
            })
            ->sortByDesc('latest_message.created_at')
            ->values();

        return view('messages.index', compact('conversations'));
    }

    // Show conversation with specific user
    public function show(User $user)
    {
        $messages = Message::where(function ($query) use ($user) {
            $query->where('sender_id', Auth::id())
                  ->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                  ->where('receiver_id', Auth::id());
        })
        ->with(['sender', 'receiver'])
        ->orderBy('created_at', 'asc')
        ->get();

        // Mark received messages as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // Check if current user follows the other user
        $isFollowing = Auth::user()->following()->where('following_id', $user->id)->exists();
        
        // Count messages sent by current user to this user
        $messagesSent = Message::where('sender_id', Auth::id())
            ->where('receiver_id', $user->id)
            ->count();

        // Message limit for non-followers (2 messages max)
        $canSendMessage = $isFollowing || $messagesSent < 2;
        $messagesRemaining = $isFollowing ? null : max(0, 2 - $messagesSent);

        return view('messages.show', compact('user', 'messages', 'canSendMessage', 'messagesRemaining', 'isFollowing'));
    }

    // Send a new message
    public function store(Request $request, User $user)
    {
        // Check if user is following
        $isFollowing = Auth::user()->following()->where('following_id', $user->id)->exists();
        
        // Check message limit for non-followers
        if (!$isFollowing) {
            $messagesSent = Message::where('sender_id', Auth::id())
                ->where('receiver_id', $user->id)
                ->count();
            
            if ($messagesSent >= 2) {
                if ($request->wantsJson()) {
                    return response()->json(['error' => 'You must follow this user to send more messages.'], 403);
                }
                return back()->with('error', 'You must follow this user to send more messages.');
            }
        }

        $request->validate([
            'message' => 'nullable|string|max:5000',
            'file' => 'nullable|file|max:102400|mimes:jpeg,png,jpg,gif,mp4,mov,pdf,doc,docx,txt',
        ]);

        $filePath = null;
        $fileType = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $mimeType = $file->getMimeType();
            
            if (str_starts_with($mimeType, 'image/')) {
                $filePath = $file->store('message_images', 'public');
                $fileType = 'image';
            } elseif (str_starts_with($mimeType, 'video/')) {
                $filePath = $file->store('message_videos', 'public');
                $fileType = 'video';
            } elseif ($mimeType === 'application/pdf') {
                $filePath = $file->store('message_files', 'public');
                $fileType = 'pdf';
            } else {
                $filePath = $file->store('message_files', 'public');
                $fileType = 'document';
            }
        }

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $user->id,
            'message' => $request->message,
            'file_path' => $filePath,
            'file_type' => $fileType,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return back()->with('success', 'Message sent successfully!');
    }

    // Fetch new messages (for real-time updates)
    public function fetchNew(Request $request, User $user)
    {
        $lastId = $request->query('last_id', 0);
        
        $messages = Message::where(function ($query) use ($user) {
            $query->where('sender_id', Auth::id())
                  ->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                  ->where('receiver_id', Auth::id());
        })
        ->where('id', '>', $lastId)
        ->orderBy('created_at', 'asc')
        ->get();

        // Mark received messages as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', Auth::id())
            ->where('id', '>', $lastId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['messages' => $messages]);
    }

    // Get unread message count
    public function unreadCount()
    {
        $count = Message::where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->count();

        return response()->json(['count' => $count]);
    }
}
