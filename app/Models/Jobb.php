<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Company;
use App\Models\Applicant;

class Jobb extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'location',
        'type',
        'company_id',
    ];
    // علاقة الوظيفة مع الشركة
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // علاقة الوظيفة مع المتقدمين
    public function applicants()
    {
        return $this->belongsToMany(Applicant::class, 'job_applicant')
            ->withPivot('applied_at');
    }
}
