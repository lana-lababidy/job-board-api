<?php

namespace App\Http\Controllers;

use App\Models\Jobb;
use App\Models\Applicant;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ApplicationController extends Controller
{
    /**
     * التقديم على وظيفة معينة
     */
    public function apply(Request $request, $jobId): JsonResponse
    {
        $validated = $request->validate([
            'applicant_id' => 'required|exists:applicants,id'
        ]);

        // 2. جلب الوظيفة والمتقدم
        $job = Jobb::find($jobId);
        $applicant = Applicant::find($validated['applicant_id']);

        if (!$job) {
            return response()->json(['error' => 'Job not found'], 404);
        }

        if (!$applicant) {
            return response()->json(['error' => 'Applicant not found'], 404);
        }

        // 3. منع التقديم المكرر
        if ($job->applicants()->where('applicant_id', $applicant->id)->exists()) {
            return response()->json([
                'error' => 'You have already applied for this job'
            ], 409); // Conflict
        }

        // 4. ربط المتقدم بالوظيفة في Pivot Table
        $job->applicants()->attach($applicant->id, [
            'applied_at' => now()
        ]);

        // 5. تحديث البيانات مع العلاقات للإرجاع
        $job->load('applicants');

        return response()->json([
            'message' => 'Application submitted successfully!',
            'job' => $job,
            'applicant' => $applicant
        ], 201);
    }

    /**
     * إلغاء التقديم على وظيفة (Bonus Feature)
     */
    public function cancel(Request $request, $jobId): JsonResponse
    {
        $validated = $request->validate([
            'applicant_id' => 'required|exists:applicants,id'
        ]);

        $job = Jobb::find($jobId);
        if (!$job) {
            return response()->json(['error' => 'Job not found'], 404);
        }

        $deleted = $job->applicants()->detach($validated['applicant_id']);

        if ($deleted === 0) {
            return response()->json(['error' => 'No application found'], 404);
        }

        return response()->json([
            'message' => 'Application cancelled successfully'
        ], 200);
    }
}
