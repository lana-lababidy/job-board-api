<?php

namespace App\Http\Controllers;

use App\Models\Jobb;
use Illuminate\Http\Request;

class JobsController extends Controller
{
   public function index(Request $request)
{
    // نبدأ Query أساسي مع الشركة المرتبطة
    $query = Jobb::with('company');

    //  فلتر حسب الموقع ?location=Remote
    if ($request->has('location') && $request->location !== null) {
        $query->where('location', $request->location);
    }

    //  فلتر حسب النوع ?type=Full-Time أو Part-Time
    if ($request->has('type') && $request->type !== null) {
        $query->where('type', $request->type);
    }

    //  فلتر حسب كلمة في العنوان ?keyword=Developer
    if ($request->has('keyword') && $request->keyword !== null) {
        $keyword = $request->keyword;
        $query->where('title', 'LIKE', "%{$keyword}%");
    }

    // تنفيذ الاستعلام بعد تطبيق كل الفلاتر المتاحة
    $jobs = $query->get();

    return response()->json($jobs, 200);
}



    public function show($id)
    {
        $job = Jobb::with('company', 'applicants')->find($id);
        if (!$job) {
            return response()->json(['error' => 'Job not found'], 404);
        }
        return response()->json($job, 200);
    }
public function store(Request $request)
{
    // الشركة اللي عاملة login
    $company = $request->user();

    // 1) نتحقق من بيانات الوظيفة (بدون company_id)
    $data = $request->validate([
        'title'       => 'required|string|max:255',
        'description' => 'required|string',
        'location'    => 'required|string|max:255',
        'type'        => 'required|in:Full-Time,Part-Time',
    ]);

    // 2) نضيف company_id للشركة من التوكن
    $data['company_id'] = $company->id;

    // 3) ننشئ الوظيفة بالـdata الكاملة
    $job = Jobb::create($data);

    return response()->json($job, 201);
}


    public function update(Request $request, $id)
    {
        $company = $request->user();

        $job = Jobb::find($id);
        if (!$job) {
            return response()->json(['error' => 'Job not found'], 404);
        }

        // منع شركة أخرى من تعديل الوظيفة
        if ($job->company_id !== $company->id) {
            return response()->json(['error' => 'Forbidden'], 403);
        }
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'location' => 'sometimes|string|max:255',
            'type' => 'sometimes|in:Full-Time,Part-Time',
        ]);

        $job->update($validated);
        return response()->json($job, 200);
    }

    public function destroy(Request $request, $id)
    {
        $company = $request->user();

        $job = Jobb::find($id);
        if (!$job) {
            return response()->json(['error' => 'Job not found'], 404);
        }


        if ($job->company_id !== $company->id) {
            return response()->json(['error' => 'Forbidden'], 403);
        }
        $job->delete();
        return response()->json(['message' => 'Job deleted'], 200);
    }
}
