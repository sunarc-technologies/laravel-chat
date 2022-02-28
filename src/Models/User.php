<?php

namespace Sunarc\LaravelChat\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    protected $guarded = [];

    public function message()
    {
        return $this->hasMany(Message::class, 'from');
    }
}
