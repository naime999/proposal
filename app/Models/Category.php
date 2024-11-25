<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Category extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    // protected $table = 'categories';

    public function relations()
    {
        return $this->hasMany(CategoryProposal::class, 'category_id', 'id');
    }
}
