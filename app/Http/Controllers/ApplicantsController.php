<?php

namespace App\Http\Controllers;
use App\Models\Jobb;
use App\Models\Applicant;
use Illuminate\Http\Request;

class ApplicantsController extends Controller
{
    public function index()
    {
        return response()->json(Applicant::all(), 200);
    }

    public function show($id)
    {
        $applicant = Applicant::with('jobbs')->find($id);
        if (!$applicant) {
            return response()->json(['error' => 'Applicant not found'], 404);
        }
        return response()->json($applicant, 200);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:applicants,email',
            'password' => 'required|string|min:6',
            'resume_link' => 'nullable|string',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $applicant = Applicant::create($validated);

        return response()->json($applicant, 201);
    }

    public function update(Request $request, $id)
    {
        $applicant = Applicant::find($id);
        if (!$applicant) {
            return response()->json(['error' => 'Applicant not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:applicants,email,' . $id,
            'password' => 'sometimes|string|min:6',
            'resume_link' => 'nullable|string',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $applicant->update($validated);
        return response()->json($applicant, 200);
    }

    public function destroy($id)
    {
        $applicant = Applicant::find($id);
        if (!$applicant) {
            return response()->json(['error' => 'Applicant not found'], 404);
        }

        $applicant->delete();
        return response()->json(['message' => 'Applicant deleted'], 200);
    }
}
