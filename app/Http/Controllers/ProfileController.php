<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\CreatesNotifications;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    use CreatesNotifications;

    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
        $this->initializeNotificationService();
    }

    /**
     * Display the user's profile page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('auth.user', [
            'title' => 'My Profile',
            'user' => auth()->user()
        ]);
    }

    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'address' => 'nullable|string|max:500',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Gagal memperbarui profil. Periksa kembali data yang dimasukkan.');
        }

        $user->update([
            'name' => $request->nama,
            'email' => $request->email,
            'phone' => $request->phone,
            'bio' => $request->bio,
            'address' => $request->address,
        ]);

        // Create notification for profile update
        $this->notifyProfileUpdated();

        return redirect()->route('profile.index')
            ->with('success', 'Profil berhasil diperbarui.')
            ->with('UpdateBerhasil', 'Informasi profil telah diperbarui.');
    }

    /**
     * Update the user's profile photo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePhoto(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'profile_photo.required' => 'Foto profil wajib dipilih.',
            'profile_photo.image' => 'File harus berupa gambar.',
            'profile_photo.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
            'profile_photo.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Gagal mengunggah foto profil. Periksa kembali file yang dipilih.');
        }

        // Check if image is valid
        if (!$request->file('profile_photo')->isValid()) {
            return redirect()->back()
                ->with('error', 'File gambar tidak valid.');
        }

        // Get image dimensions
        $image = getimagesize($request->file('profile_photo'));
        if ($image === false) {
            return redirect()->back()
                ->with('error', 'File bukan gambar yang valid.');
        }

        $minWidth = 100;
        $minHeight = 100;

        if ($image[0] < $minWidth || $image[1] < $minHeight) {
            return redirect()->back()
                ->with('error', "Gambar terlalu kecil. Minimal {$minWidth}x{$minHeight} piksel.");
        }

        // Delete old profile photo if exists
        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        // Store new profile photo
        $photoPath = $request->file('profile_photo')->store('profile-photos', 'public');

        $user->update([
            'profile_photo' => $photoPath,
        ]);

        // Create notification for profile photo update
        $this->notifyProfilePhotoUpdated();

        return redirect()->route('profile.index')
            ->with('success', 'Foto profil berhasil diperbarui.')
            ->with('UpdateBerhasil', 'Foto profil telah diperbarui.');
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'new_password.required' => 'Kata sandi baru wajib diisi.',
            'new_password.min' => 'Kata sandi baru minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Gagal memperbarui kata sandi.');
        }

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->with('error', 'Kata sandi saat ini tidak cocok.')
                ->withInput();
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        // Create notification for password change
        $this->createNotification(\App\Enums\NotificationType::PASSWORD_CHANGED, [
            'message' => 'Kata sandi Anda telah diubah'
        ]);

        return redirect()->route('profile.index')
            ->with('success', 'Kata sandi berhasil diperbarui.')
            ->with('UpdateBerhasil', 'Kata sandi telah diperbarui.');
    }

    /**
     * Remove the user's profile photo.
     *
     * @return \Illuminate\Http\Response
     */
    public function removePhoto()
    {
        $user = auth()->user();

        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        $user->update([
            'profile_photo' => null,
        ]);

        return redirect()->route('profile.index')
            ->with('success', 'Foto profil berhasil dihapus.')
            ->with('UpdateBerhasil', 'Foto profil telah dihapus.');
    }
}
