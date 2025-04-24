<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = ['user_id', 'subject', 'status', 'admin_unread_count', 'user_unread_count', 'last_message_at'];

    protected $dates = ['last_message_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function incrementUnreadCount()
    {
        $this->increment('admin_unread_count');
    }

    public function resetUnreadCount()
    {
        $this->update(['admin_unread_count' => 0]);
    }

    public function incrementUserUnreadCount()
    {
        $this->increment('user_unread_count');
    }

    public function resetUserUnreadCount()
    {
        $this->update(['user_unread_count' => 0]);
    }

    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }
}
