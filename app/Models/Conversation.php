<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

// If conversation_name = NULL, show recipients of chatbox
// i.e. conversation of user1 & user2
// user1 will see user2 as conversation_name and vice-versa
class Conversation extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'conversation_name'
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function groupMembers(): HasMany
    {
        return $this->hasMany(GroupMember::class);
    }
}
