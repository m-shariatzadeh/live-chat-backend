<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentPresence extends Model
{
    protected $primaryKey = 'agent_id';
    public $incrementing = false;

    protected $guarded = [];

    protected $casts = [
        'is_online' => 'boolean',
        'last_seen_at' => 'datetime',
    ];
}