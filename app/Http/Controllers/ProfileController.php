<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\CreatesNotifications;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

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
     * Update the user's profile photo with cropping.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePhotoCropped(Request $request)
    {
        $user = auth()->user();

        \Log::info('updatePhotoCropped called');
        \Log::info('Request has profile_photo file:', ['exists' => $request->hasFile('profile_photo')]);
        \Log::info('Request has croppedImage:', ['exists' => $request->has('croppedImage')]);

        try {
            $imageData = null;
            $tempPath = null;

            // Check if we have a file upload (from Blob conversion)
            if ($request->hasFile('profile_photo')) {
                $file = $request->file('profile_photo');
                \Log::info('File upload detected:', ['name' => $file->getClientOriginalName(), 'size' => $file->getSize(), 'type' => $file->getMimeType()]);

                if (!$file->isValid()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'File upload tidak valid.'
                    ], 400);
                }

                $tempPath = $file->getPathname();
            }
            // Check if we have base64 cropped image data
            elseif ($request->has('croppedImage') && !empty($request->croppedImage)) {
                \Log::info('Base64 cropped image detected');
                $base64Data = $request->croppedImage;

                // Validate base64 format
                if (!preg_match('/^data:image\/(\w+);base64,/', $base64Data, $matches)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Format gambar tidak valid.'
                    ], 400);
                }

                // Decode base64 data
                $imageData = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $base64Data));

                if ($imageData === false) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal mendekode gambar.'
                    ], 400);
                }

                // Get image info to validate
                $imageInfo = @getimagesizefromstring($imageData);
                if ($imageInfo === false) {
                    return response()->json([
                        'success' => false,
                        'message' => 'File bukan gambar yang valid.'
                    ], 400);
                }

                // Create temp file
                $tempPath = tempnam(sys_get_temp_dir(), 'cropped_');
                file_put_contents($tempPath, $imageData);
                \Log::info('Created temp file:', ['path' => $tempPath, 'size' => strlen($imageData)]);
            }
            else {
                return response()->json([
                    'success' => false,
                    'message' => 'Foto profil wajib dipilih.'
                ], 422);
            }

            // Get image dimensions
            $imageInfo = getimagesize($tempPath);
            if ($imageInfo === false) {
                // Clean up temp file if exists
                if ($tempPath && file_exists($tempPath) && !$request->hasFile('profile_photo')) {
                    unlink($tempPath);
                }
                return response()->json([
                    'success' => false,
                    'message' => 'File bukan gambar yang valid.'
                ], 400);
            }

            $minWidth = 100;
            $minHeight = 100;

            if ($imageInfo[0] < $minWidth || $imageInfo[1] < $minHeight) {
                // Clean up temp file if exists
                if ($tempPath && file_exists($tempPath) && !$request->hasFile('profile_photo')) {
                    unlink($tempPath);
                }
                return response()->json([
                    'success' => false,
                    'message' => "Gambar terlalu kecil. Minimal {$minWidth}x{$minHeight} piksel."
                ], 422);
            }

            // Delete old profile photo if exists
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            // Generate unique filename
            $filename = 'profile_' . $user->id . '_' . time() . '.jpg';
            $directory = 'profile-photos';

            // Create directory if it doesn't exist
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            // Process and resize image to 200x200
            $image = Image::make($tempPath);

            // Resize maintaining aspect ratio, then crop to 200x200
            $image->fit(200, 200, function ($constraint) {
                $constraint->upsize();
            }, 'center');

            // Save the processed image
            $path = storage_path('app/public/' . $directory . '/' . $filename);
            $image->save($path, 90);

            // Clean up temp file if it was created from base64
            if ($tempPath && file_exists($tempPath) && !$request->hasFile('profile_photo')) {
                unlink($tempPath);
            }

            // Get relative path for database
            $photoPath = $directory . '/' . $filename;

            // Update user profile photo
            $user->update([
                'profile_photo' => $photoPath,
            ]);

            // Create notification for profile photo update
            $this->notifyProfilePhotoUpdated();

            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil diperbarui.',
                'photo_url' => asset('storage/' . $photoPath)
            ]);

        } catch (\Exception $e) {
            \Log::error('Profile photo upload error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses gambar. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Update the user's profile photo (legacy method without cropping).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePhoto(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ], [
            'profile_photo.required' => 'Foto profil wajib dipilih.',
            'profile_photo.image' => 'File harus berupa gambar.',
            'profile_photo.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau webp.',
            'profile_photo.max' => 'Ukuran gambar maksimal 5MB.',
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
        $image = getimagesize($request->file('profile_photo')->getPathname());
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

        // Process and resize image
        $file = $request->file('profile_photo');
        $filename = 'profile_' . $user->id . '_' . time() . '.jpg';
        $directory = 'profile-photos';

        // Create directory if it doesn't exist
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        // Process and resize to 200x200
        $image = Image::make($file->getPathname());
        $image->fit(200, 200, function ($constraint) {
            $constraint->upsize();
        }, 'center');

        // Save the processed image
        $path = storage_path('app/public/' . $directory . '/' . $filename);
        $image->save($path, 90);

        $photoPath = $directory . '/' . $filename;

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
    public function destroy()
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
