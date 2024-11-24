<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name', 'email', 'username', 'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function sharedtasks()
    {
        return $this->hasMany(SharedTaskList::class);
    }

    public function taskLists()
    {
        return $this->hasMany(TaskList::class);
    }
}

