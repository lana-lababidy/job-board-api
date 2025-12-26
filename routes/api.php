<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\ApplicantsController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CompanyAuthController;
use App\Http\Controllers\ApplicantAuthController;

/*
|--------------------------------------------------------------------------
| Auth Routes (Register/Login/Logout)
|--------------------------------------------------------------------------
*/

// فحص التوكن للـApplicant (للتجربة)
Route::middleware('auth:sanctum')->get('/check-applicant', function (Request $request) {
    return $request->user();
});

// Auth للشركات
Route::prefix('company')->group(function () {
    Route::post('register', [CompanyAuthController::class, 'register']);
    Route::post('login',    [CompanyAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [CompanyAuthController::class, 'logout']);
    });
});

// Auth للمتقدمين
Route::prefix('applicant')->group(function () {
    Route::post('register', [ApplicantAuthController::class, 'register']);
    Route::post('login',    [ApplicantAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [ApplicantAuthController::class, 'logout']);
    });
});

/*
|--------------------------------------------------------------------------
| Public CRUD Routes (قراءة فقط بدون Auth)
|--------------------------------------------------------------------------
*/

// الشركات: ممكن تخلي القراءة للجميع
Route::get('companies',        [CompaniesController::class, 'index']);
Route::get('companies/{company}', [CompaniesController::class, 'show']);

// الوظائف: أي شخص يقدر يشوف
Route::get('jobs',        [JobsController::class, 'index']);
Route::get('jobs/{job}',  [JobsController::class, 'show']);

// المتقدمين: عادة لا نعرضهم للعامة، لكن لو حابة تبقيهم مفتوحين:
Route::get('applicants',        [ApplicantsController::class, 'index']);
Route::get('applicants/{applicant}', [ApplicantsController::class, 'show']);

/*
|--------------------------------------------------------------------------
| Protected CRUD Routes (تحتاج توكن)
|--------------------------------------------------------------------------
*/

// شركات مسجّلة فقط تقدر تنشئ/تعدّل/تحذف شركاتها (لو حابة تحميها)
Route::middleware('auth:sanctum')->group(function () {

    // إدارة الشركات (اختياري تحميها)
    Route::post('companies',        [CompaniesController::class, 'store']);
    Route::put('companies/{company}',   [CompaniesController::class, 'update']);
    Route::delete('companies/{company}',[CompaniesController::class, 'destroy']);

    // إدارة الوظائف: الشركة اللي معها توكن تقدر تنشئ/تعدّل/تحذف
    Route::post('jobs',         [JobsController::class, 'store']);
    Route::put('jobs/{job}',    [JobsController::class, 'update']);
    Route::delete('jobs/{job}', [JobsController::class, 'destroy']);

    // إدارة المتقدمين (لو حابة تحمي إنشاء/تعديل بياناتهم)
    Route::post('applicants',         [ApplicantsController::class, 'store']);
    Route::put('applicants/{applicant}',    [ApplicantsController::class, 'update']);
    Route::delete('applicants/{applicant}', [ApplicantsController::class, 'destroy']);

    // التقديم / إلغاء التقديم على الوظائف (محمي للـApplicant المسجّل)
    Route::post('jobs/{job}/apply',  [ApplicationController::class, 'apply']);
    Route::delete('jobs/{job}/cancel', [ApplicationController::class, 'cancel']);
});

/*
|--------------------------------------------------------------------------
| مثال بسيط على /user (اختياري)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
/* فلتر المكان فقط:
jobs?location=Remote

فلتر النوع فقط:
jobs?type=Full-Time

فلتر كلمة مفتاحية:
jobs?keyword=Developer

أكثر من فلتر معًا:
jobs?location=Remote&type=Full-Time&keyword=Backend */