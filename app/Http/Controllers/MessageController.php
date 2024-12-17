<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\MessageNotification;
use Illuminate\Support\Facades\Notification;

class MessageController extends Controller
{
    public function index($receiverId)
    {
        $users = User::where('name', '!=', Auth::user()->name)
                        ->where('id', '=', $receiverId)
                        ->get();

        $receiverId = User::findOrFail($receiverId);

        return view('chat.index', compact('users'));
    }

    public function fetchMessages($receiverId)
    {
        $messages = Message::where(function ($query) use ($receiverId) {
            $query->where('sender_id', Auth::id())
                  ->where('receiver_id', $receiverId);
        })->orWhere(function ($query) use ($receiverId) {
            $query->where('sender_id', $receiverId)
                  ->where('receiver_id', Auth::id());
        })
        ->with('sender:id,name', 'receiver:id,name')
        ->orderBy('created_at', 'asc')
        ->get();

        return response()->json($messages);
    }

    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        $message = new Message();
        $message->sender_id = Auth::id();
        $message->receiver_id = $request->receiver_id;
        $message->message = $request->message;
        $message->save();

         // Send notification
        $receiver = User::findOrFail($request->receiver_id);
        Notification::send($receiver, new MessageNotification($message));

        return response()->json(['success' => true, 'message' => 'Message sent successfully.']);
    }
}
