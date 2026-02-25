<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $guarded = [];

    public function sessions()
    {
        return $this->hasMany(VisitorSession::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }
}
