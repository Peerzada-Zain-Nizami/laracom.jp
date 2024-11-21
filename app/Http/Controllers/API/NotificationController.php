<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\UserNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $unreadNotifications = $user->unreadNotifications;
        $readNotifications = $user->readNotifications;

        // Check the counts of unread and read notifications
        \Log::info('Unread Notifications Count:', ['count' => $unreadNotifications->count()]);
        \Log::info('Read Notifications Count:', ['count' => $readNotifications->count()]);

        return response()->json([
            'unread' => $unreadNotifications,
            'read' => $readNotifications,
        ]);
    }

    public function markAsRead(Request $request)
    {
        $unreadNotifications = auth()->user()->unreadNotifications;
        $unreadNotifications->markAsRead();
        \Log::info('Unread Notifications marked as read:', ['unread_notifications' => $unreadNotifications]);
        return response()->json(['message' => 'Notifications marked as read successfully.']);
    }

    public function sendNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'message' => 'required|string',
            'url' => 'nullable|url',
            'user_id' => 'nullable|exists:users,id', // Optional: agar specific user ko bhejna ho
        ]);

        $data = [
            'title' => $request->title,
            'message' => $request->message,
            'url' => $request->url,
        ];

        if ($request->has('user_id')) {
            $user = User::find($request->user_id);
            $user->notify(new UserNotification($data));
        } else {
            // All users ko send karne ke liye
            $users = User::all();
            foreach ($users as $user) {
                $user->notify(new UserNotification($data));
            }
        }
        return response()->json(['message' => 'Notifications Send successfully.']);
    }

}
