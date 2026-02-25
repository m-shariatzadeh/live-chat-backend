<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConversationTag extends Model
{
    protected $guarded = [];

    public function conversations()
    {
        return $this->belongsToMany(
            Conversation::class,
            'conversation_tag_items',
            'tag_id',
            'conversation_id'
        );
    }
}