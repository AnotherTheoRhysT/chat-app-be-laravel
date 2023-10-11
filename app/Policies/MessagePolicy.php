<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Message;
use App\Models\GroupMember;

class MessagePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function create(User $user, int $conversationId) : bool
    {
        return GroupMember::where('conversation_id', $conversationId)
            ->where('user_id', $user->id)
            ->first() !== NULL;
    }

    public function get(User $user, int $conversationId) : bool
    {
        return GroupMember::where('conversation_id', $conversationId)
            ->where('user_id', $user->id)
            ->first() !== NULL;
    }
}
