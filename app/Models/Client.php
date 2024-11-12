<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Client extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    public function userInfo()
    {
        return $this->hasOne(User::class, 'id', 'client_id');
    }

}
