<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupInvite extends Model
{
    public $fillable = [
        'email'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class);
    }
}
