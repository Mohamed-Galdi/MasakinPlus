<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['ticket_id', 'sender', 'content'];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($message) {
            $ticket = $message->ticket;
            $ticket->update(['last_message_at' => $message->created_at]);

            if ($message->sender === 'user') {
                $ticket->incrementUnreadCount();
            } elseif ($message->sender === 'admin') {
                $ticket->incrementUserUnreadCount();
            }
        });
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender');
    }
}
