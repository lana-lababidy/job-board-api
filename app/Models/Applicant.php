<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Jobb;

class Applicant extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'resume_link',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // علاقة المتقدم بالوظائف
    public function jobbs()
    {
        return $this->belongsToMany(Jobb::class, 'job_applicant')
            ->withPivot('applied_at');
    }
}
