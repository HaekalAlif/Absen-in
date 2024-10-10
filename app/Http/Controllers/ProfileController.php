<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
        public function edit(Request $request): View
    {
        return view('profile.profile', [
            'user' => $request->user(),
        ]);
    }



    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function uploadImage(Request $request)
    {
        // Validasi file gambar
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Ambil user yang sedang login
        $user = Auth::user();

        // Hapus gambar lama jika ada
        if ($user->profile_image && file_exists(public_path('profile_images/' . $user->profile_image))) {
            unlink(public_path('profile_images/' . $user->profile_image));
        }

        // Simpan gambar yang diupload ke public/profile_images
        $image = $request->file('profile_image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('profile_images'), $imageName);

        // Simpan nama gambar di database
        $user->profile_image = $imageName;
        $user->save();

        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Foto profil berhasil diupload.');
    }

}
