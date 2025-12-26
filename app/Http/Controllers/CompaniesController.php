<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
class CompaniesController extends Controller
{
    // List all companies
    public function index() {
        return response()->json(Company::all(), 200);
    }

    // Show single company
    public function show($id) {
        $company = Company::find($id);
        if (!$company) {
            return response()->json(['error' => 'Company not found'], 404);
        }
        return response()->json($company, 200);
    }

    // Create new company
    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email',
            'password' => 'required|string|min:6',
            'description' => 'nullable|string',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        $company = Company::create($validated);

        return response()->json($company, 201);
    }

    // Update company
    public function update(Request $request, $id) {
        $company = Company::find($id);
        if (!$company) {
            return response()->json(['error' => 'Company not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:companies,email,'.$id,
            'password' => 'sometimes|string|min:6',
            'description' => 'nullable|string',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $company->update($validated);

        return response()->json($company, 200);
    }

    // Delete company
    public function destroy($id) {
        $company = Company::find($id);
        if (!$company) {
            return response()->json(['error' => 'Company not found'], 404);
        }

        $company->delete();
        return response()->json(['message' => 'Company deleted'], 200);
    }
}
