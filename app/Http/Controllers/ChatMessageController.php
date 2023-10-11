<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;

class ChatMessageController extends Controller
{
    public function get(Request $request, int $conversationId) : JsonResponse
    {
        if (Gate::denies('get-messages', $conversationId)) {
            return response()->json([
                'status' => 404,
                'message' => 'Conversation does not exist'  // for security reasons instead of unauthorized (so they don't know a convo exists)
            ]);
        }

        $messages = Message::where('conversation_id', $conversationId)
            ->with('user')
            ->get();

        return response()->json([
            'status' => 200,
            'messages' => $messages
        ]);
    }

    public function send(Request $request) : JsonResponse
    {
        $validatedData = $request->validate([
            'conversation_id' => ['required', 'int'],
            'message_text' => ['required', 'string']
        ]);

        // Check if user belongs in conversation
        if (Gate::denies('send-message', $validatedData['conversation_id'])) {
            return response()->json([
                'status' => 404,
                'message' => 'Conversation does not exist'  // for security reasons instead of unauthorized (so they don't know a convo exists)
            ]);
        }

        $message = Message::create([
                'message_text' => $validatedData['message_text'],
                'user_id' => $request->user()->id,
                'conversation_id' => $validatedData['conversation_id'],
            ]);

        Broadcast( new MessageSent($message) )->toOthers();
        return response()->json([
            'status' => 201,
            'message' => 'Message sent successfully'
        ]);
    }
}
