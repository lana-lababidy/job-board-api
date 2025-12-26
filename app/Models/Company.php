<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Company extends Authenticatable
{

    use HasApiTokens, HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'description',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // علاقة الشركة مع الوظائف
    public function jobs()
    {
        return $this->hasMany(Jobb::class);
    }
}
