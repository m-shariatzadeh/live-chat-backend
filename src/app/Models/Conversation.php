<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $guarded = [];

    protected $casts = [
        'started_at' => 'datetime',
        'first_response_at' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }

    public function session()
    {
        return $this->belongsTo(VisitorSession::class, 'session_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function assignments()
    {
        return $this->hasMany(ConversationAssignment::class);
    }

    public function tags()
    {
        return $this->belongsToMany(
            ConversationTag::class,
            'conversation_tag_items',
            'conversation_id',
            'tag_id'
        );
    }

    public function departments()
    {
        return $this->belongsToMany(
            Department::class,
            'conversation_department'
        );
    }
}
