<?php

namespace Sunarc\LaravelChat\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Message extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'from');
    }
    public function getFileAttribute($file)
    {
        if($file)
        return $file = Storage::url('files/'.$file);
    }
}
