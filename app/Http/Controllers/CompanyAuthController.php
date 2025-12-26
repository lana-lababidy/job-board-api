<?php
namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CompanyAuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email',
            'password' => 'required|string|min:6|confirmed', // استخدمي password_confirmation
            'description' => 'nullable|string',
        ]);

        $company = Company::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'description' => $data['description'] ?? null,
        ]);

        $token = $company->createToken('company-token')->plainTextToken;

        return response()->json([
            'message' => 'Company registered successfully',
            'company' => $company,
            'token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }
        public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $company = Company::where('email', $data['email'])->first();

        if (! $company || ! Hash::check($data['password'], $company->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // حذف التوكنات القديمة لو حبيتي
        $company->tokens()->delete();

        $token = $company->createToken('company-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'company' => $company,
            'token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

}