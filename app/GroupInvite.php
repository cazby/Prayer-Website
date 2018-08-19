<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class GroupInvite extends Model
{
    use Notifiable;

    public $fillable = [
        'email'
    ];

    public $dates = [
        'accepted_at'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class);
    }

    public function accept()
    {
        $this->accepted_at = now();
        $this->save();

        $this->group->users()->attach($this->receiver);
    }
}
