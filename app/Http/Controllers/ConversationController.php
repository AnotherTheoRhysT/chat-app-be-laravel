<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\ConversationCreated;
use App\Models\Conversation;
use App\Models\GroupMember;
use App\Models\User;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class ConversationController extends Controller
{
    public function createConversation(Request $request) : JsonResponse
    {
        $validatedData = $request->validate([
            'recipients' => ['required', 'array'],
            'message' => ['filled', 'string'],
            'conversation_name' => ['filled', 'string'],
        ]);

        // Check if conversation exists already
        // GroupMember::where();

        // Check if recipients are existing users
        DB::table('user')->create();
        $recipients = User::select('id')
            ->whereIn('id', $validatedData['recipients'])
            ->get()
            ->map(fn(User $user) => $user->id);

        if (array_diff($validatedData['recipients'], $recipients->toArray())) {
            return response()->json([
                'status' => 406,
                'message' => 'Some recipient users do not exist'
            ]);
        }

        ConversationCreated::dispatch($validatedData);

        return response()->json([

        ]);
    }


    public function get(Request $request) : JsonResponse
    {
        $conversations = Conversation::whereNull('deleted_at')
            ->whereHas('groupMembers', function(Builder $query) use (&$request) {
                $query->where('user_id', $request->user()->id);
            })
            ->orderByDesc('last_message_timestamp')
            ->with('groupMembers.user')
            // ->ddRawSql();
            ->get()
            ->map(function (Conversation $conversation) use (&$request) {
                $conversationName = ($conversation->conversation_name !== NULL) 
                    ? $conversation->conversation_name
                    : $conversation->groupMembers
                        ->map(function (GroupMember $groupMember) use (&$request) {
                            if ($groupMember->user_id !== $request->user()->id) {
                                $nameArr = explode(" ", $groupMember->user->name);
                                return $nameArr[0] . (count($nameArr) == 1 ? '' : ' '.$nameArr[1]);
                            }
                        })
                        ->filter(fn (?string $name, int $key) => $name !== NULL)
                        ->sort()
                        ->implode(', ');
                return ['id' => $conversation->id, 'conversation_name' => $conversationName];
            });

        return response()->json([
            'status' => 200,
            'conversations' => $conversations
        ]);
    }
}