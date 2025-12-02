<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $data = $request->only('name', 'email');

        if ($request->filled('whatsapp_number')) {
            $data['whatsapp_number'] = $request->input('whatsapp_number');
        }

        if ($request->hasFile('id_card_photo')) {
            $request->validate([
                'id_card_photo' => 'nullable|image|max:2048',
            ]);

            $file = $request->file('id_card_photo');
            $path = $file->store('id_cards', 'public');
            $data['id_card_photo_path'] = $path;
        }

        $user->update($data);

        return back()->with('success', 'Profil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Verify current password
        if (!Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak cocok.'])->withInput();
        }

        // Update password
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return back()->with('success', 'Kata sandi berhasil diperbarui.');
    }
}