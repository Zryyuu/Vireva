<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Petugas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminPetugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Load all users with role 'admin' or 'superadmin'
        $users = User::with('petugas')->whereIn('role', ['admin', 'superadmin'])->get();
        return view('admin.petugas.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.petugas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_petugas' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->nama_petugas,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        Petugas::create([
            'user_id' => $user->id,
            'nama_petugas' => $request->nama_petugas,
            'jabatan' => 'Admin Vireva',
        ]);

        return redirect()->route('admin.petugas.index')->with('success', 'Petugas baru berhasil didaftarkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::with('petugas')->findOrFail($id);
        
        // Pastikan hanya admin yang bisa diedit
        if ($user->role !== 'admin') {
            abort(403);
        }

        return view('admin.petugas.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        if ($user->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'nama_petugas' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class.',email,'.$user->id],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $user->update([
            'name' => $request->nama_petugas,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        if ($user->petugas) {
            $user->petugas->update([
                'nama_petugas' => $request->nama_petugas,
                'jabatan' => 'Admin Vireva',
            ]);
        } else {
            Petugas::create([
                'user_id' => $user->id,
                'nama_petugas' => $request->nama_petugas,
                'jabatan' => 'Admin Vireva',
            ]);
        }

        return redirect()->route('admin.petugas.index')->with('success', 'Data petugas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // Proteksi Super Admin (Gunakan role, bukan email)
        if ($user->role === 'superadmin') {
            return redirect()->route('admin.petugas.index')->with('error', 'Super Admin tidak boleh dihapus dari sistem!');
        }

        // Jangan izinkan delete diri sendiri
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.petugas.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.');
        }

        if ($user->petugas) {
            $user->petugas->delete();
        }
        $user->delete();

        return redirect()->route('admin.petugas.index')->with('success', 'Petugas berhasil dihapus dari sistem.');
    }
}
