<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{receiverId}', function ($user, $receiverId) {
    return (int) $user->id === (int) $receiverId || (int) $user->id === Auth::id();
});

Broadcast::channel('App.Models.User.{receiverId}', function ($user, $receiverId) {
    return (int) $user->id === (int) $receiverId || (int) $user->id === Auth::id();
});

Broadcast::channel('presence.chat.', function ($user) {
    return ['id' => $user->id, 'name' => $user->name];
});
