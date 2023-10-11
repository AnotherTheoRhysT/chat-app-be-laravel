<?php

namespace Database\Seeders;

use App\Models\GroupMember;
use App\Models\Message;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GroupMember::select('user_id', 'conversation_id')
            ->get()
            ->map(function(GroupMember $groupMember) {
                Message::factory()->create([
                    'user_id' => $groupMember->user_id,
                    'conversation_id' => $groupMember->conversation_id,
                ]);
            });
    }
}
