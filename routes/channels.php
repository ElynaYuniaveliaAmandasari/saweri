<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function (User $user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('donations.user.{userId}', function (User $user, int $userId) {
    return $user->id === $userId;
});
