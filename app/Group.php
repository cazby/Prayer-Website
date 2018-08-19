<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public $fillable = [
        'name',
        'description',
        'url',
        'private',
    ];

    public $casts = [
        'user_id' => 'int',
        'private' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->using(GroupUser::class)->withTimestamps();
    }

    public function invites()
    {
        return $this->hasMany(GroupInvite::class);
    }

    public function scopePublic($query)
    {
        return $query->where('private', false);
    }
}
