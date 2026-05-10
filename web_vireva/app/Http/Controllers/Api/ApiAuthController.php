<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tamu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ApiAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::with('tamu')->where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email atau password salah.',
            ], 401);
        }

        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
                'phone' => $user->tamu->no_hape ?? $user->phone ?? null,
                'nik'   => $user->tamu->no_identitas ?? null,
                'alamat'=> $user->tamu->alamat ?? null,
            ],
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        return DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'tamu',
            ]);

            // Create Tamu profile automatically
            Tamu::create([
                'user_id' => $user->id,
                'nama_tamu' => $user->name,
                'no_hape' => null,
                'no_identitas' => null,
                'alamat' => null,
            ]);

            $token = $user->createToken('mobile_device')->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'role'  => $user->role,
                    'phone' => null,
                    'nik'   => null,
                    'alamat'=> null,
                ],
            ], 201);
        });
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    // ─── Profile ─────────────────────────────────────────────────────────────

    public function getProfile(Request $request)
    {
        $user = $request->user()->load('tamu');
        return response()->json([
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $user->role,
            'phone' => $user->tamu->no_hape ?? $user->phone ?? null,
            'nik'   => $user->tamu->no_identitas ?? null,
            'alamat'=> $user->tamu->alamat ?? null,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user()->load('tamu');

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'nik'   => 'nullable|string|max:25',
            'alamat'=> 'nullable|string|max:500',
        ]);

        return DB::transaction(function () use ($request, $user) {
            $user->update([
                'name'  => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

            if ($user->tamu) {
                $user->tamu->update([
                    'nama_tamu' => $request->name,
                    'no_hape' => $request->phone,
                    'no_identitas' => $request->nik,
                    'alamat' => $request->alamat,
                ]);
            } else if ($user->role === 'tamu') {
                Tamu::create([
                    'user_id' => $user->id,
                    'nama_tamu' => $user->name,
                    'no_hape' => $request->phone,
                    'no_identitas' => $request->nik,
                    'alamat' => $request->alamat,
                ]);
            }

            return response()->json([
                'message' => 'Profil berhasil diperbarui.',
                'user' => [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'role'  => $user->role,
                    'phone' => $user->tamu->no_hape ?? $user->phone ?? null,
                    'nik'   => $user->tamu->no_identitas ?? null,
                    'alamat'=> $user->tamu->alamat ?? null,
                ],
            ]);
        });
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|string|min:8|confirmed',
        ]);

        if (! Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Password lama tidak sesuai.'], 422);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return response()->json(['message' => 'Password berhasil diperbarui.']);
    }
}
