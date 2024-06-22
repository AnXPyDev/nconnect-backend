<?php

namespace App\Models;

use App\Mail\UnregisterMail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\HasApiTokens;

class User extends AuthUser
{
    use HasFactory, HasApiTokens;

    public function token() {
        return $this->createToken('auth_token',
            ['user']
        );
    }

    public function timeslots() {
        return $this->belongsToMany(Timeslot::class, 'user_timeslot_pivot');
    }
}
