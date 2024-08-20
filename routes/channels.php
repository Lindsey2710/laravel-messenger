<?php

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('online', function (User $user) {
    Log::info('Auth request for online channel:', ['user' => $user]);
    return $user ? new UserResource($user) : null;
});

Broadcast::channel('message.user.{userId1}-{userId2}', function (User $user, int $UserId1, int $UserId2) {
    Log::info('Auth request for user message channel:', ['user' => $user, 'userId1' => $UserId1, 'userId2' => $UserId2]);
    return $user->id === $UserId1 || $user->id === $UserId2 ? $user : null;
});

Broadcast::channel('message.group.{groupId}', function (User $user, int $groupId) {
    Log::info('Auth request for group message channel:', ['user' => $user, 'groupId' => $groupId]);
    return $user->groups->contains('id', $groupId) ? $user : null;
});

Broadcast::channel('group.deleted.{groupId}', function (User $user, int $groupId) {
    Log::info('Auth request for group message channel:', ['user' => $user, 'groupId' => $groupId]);
    return $user->groups->contains('id', $groupId) ? $user : null;
});
