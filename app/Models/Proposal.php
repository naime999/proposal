<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Proposal extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    public function sections()
    {
        return $this->hasMany(ProposalSection::class, 'proposal_id', 'id')->orderBy('sort');
    }

    public function creator()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function client()
    {
        return $this->hasOne(User::class, 'id', 'client_id');
    }

    public function clientSignature()
    {
        return $this->hasOne(ProposalSignature::class, 'proposal_id', 'id')->where('type', 2);
    }

    public function adminSignature()
    {
        return $this->hasOne(ProposalSignature::class, 'proposal_id', 'id')->where('type', 1);
    }

}
