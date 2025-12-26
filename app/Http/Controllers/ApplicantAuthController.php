<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApplicantAuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:applicants,email',
            'password' => 'required|string|min:6|confirmed',
            'resume_link' => 'nullable|string',
        ]);

        $applicant = Applicant::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'resume_link' => $data['resume_link'] ?? null,
        ]);

        $token = $applicant->createToken('applicant-token')->plainTextToken;

        return response()->json([
            'message' => 'Applicant registered successfully',
            'applicant' => $applicant,
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

        $applicant = Applicant::where('email', $data['email'])->first();

        if (! $applicant || ! Hash::check($data['password'], $applicant->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $applicant->tokens()->delete();

        $token = $applicant->createToken('applicant-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'applicant' => $applicant,
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
