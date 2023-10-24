<?php
declare(strict_types=1);

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     * @param Message $message
     */
    public function __construct(private readonly Message $message)
    {
        
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.'. $this->message->conversation_id),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'message' => $this->message,
            'user' => $this->message->user,
            // 'message_text' => $this->message->message_text,
            // 'sent_timestamp' => $this->message->sent_timestamp,
            // 'deleted_at' => $this->message->deleted_at,
            // 'sender' => [
            //     'name' => $this->message->user->name,
            // ]
        ];
    }
}
