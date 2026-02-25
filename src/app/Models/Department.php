<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $guarded = [];

    public function conversations()
    {
        return $this->belongsToMany(
            Conversation::class,
            'conversation_department'
        );
    }
}
