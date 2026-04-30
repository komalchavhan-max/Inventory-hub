<?php

namespace App\Events;

use App\Models\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification;
    public $userId;

    public function __construct($notification, $userId){
        $this->notification = $notification;
        $this->userId = $userId;
    }

    public function broadcastOn(){
        return new PrivateChannel('notifications.' . $this->userId);
    }

    public function broadcastAs(){
        return 'new-notification';
    }

    public function broadcastWith(){
        return [
            'id' => $this->notification->id,
            'message' => $this->notification->message,
            'status' => $this->notification->status,
            'created_at' => $this->notification->created_at->diffForHumans(),
            'is_read' => $this->notification->is_read
        ];
    }
}