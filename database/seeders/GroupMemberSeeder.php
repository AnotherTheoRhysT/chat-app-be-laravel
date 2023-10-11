<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\GroupMember;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userIds = User::select('id')
            ->get()
            ->map(fn(User $user) => $user->id);
        $userCount = User::count();

        $i = 0;
        // Private Convos
        Conversation::whereNull('conversation_name')
            ->get()
            ->map(function(Conversation $convo) use($userIds, &$i, $userCount) {
                GroupMember::factory()->create(['conversation_id' => $convo->id, 'user_id' => $userIds[$i++ % $userCount]]);
                GroupMember::factory()->create(['conversation_id' => $convo->id, 'user_id' => $userIds[$i++ % $userCount]]);
        });

        // Group Chats
        Conversation::whereNotNull('conversation_name')
            ->get()
            ->map(function(Conversation $convo) use($userIds, &$i, $userCount) {
                for ($j=0, $count = rand(3, 5) ; $j < $count; $j++) { 
                    GroupMember::factory()->create(['conversation_id' => $convo->id, 'user_id' => $userIds[$i++ % $userCount]]);
                }
        });
    }
}
