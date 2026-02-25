<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorSession extends Model
{
    protected $guarded = [];

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'session_id');
    }
}
