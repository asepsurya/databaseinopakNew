<?php

namespace App\Http\Controllers;
use App\Enums\NotificationType;
use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Ikm;
use App\Models\Province;
use App\Models\Regency;
use App\Models\User;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MyController extends Controller
{
    // Ollama Proxy - Forward requests to myollama.scrollwebid.com
    // Maps /api/generate to /api/tags
    public function ollamaProxy(Request $request, $endpoint)
    {
        $targetUrl = 'https://myollama.scrollwebid.com';

        // Map /api/generate to /api/tags
        $mappedEndpoint = str_replace('api/generate', 'api/tags', $endpoint);

        $fullTargetPath = $targetUrl . '/' . $mappedEndpoint;

        try {
            $client = Http::withOptions([
                'verify' => false,
                'timeout' => 60,
            ]);

            $method = $request->method();
            $headers = $request->header();
            $body = $request->getContent();

            unset($headers['host']);
            unset($headers['Host']);

            $response = $client->withHeaders($headers)->$method($fullTargetPath, $body ? json_decode($body, true) : []);

            return response()->json([
                'data' => $response->json(),
                'status' => $response->status(),
            ], $response->status());

        } catch (\Exception $e) {
            Log::error('Ollama Proxy Error: ' . $e->getMessage());

            return response()->json([
                'error' => 'Failed to connect to Ollama server',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function brainstorming(){
        return view('pages.brainstorming.view',[
            'title'=>'Sesi Brainstorming'
        ]);
    }
    public function dataikm(){
        return view('pages.ikm.show',[
            'title'=>'Data IKM'
        ]);
    }
    public function brainstormingInsert(){

        return view('pages.brainstorming.insert',[
            'title'=>'Form Brainstorming',
            'provinsi'=>Province::Firstwhere('id',auth()->user()->id_provinsi),
            'kota'=>Regency::where('id',auth()->user()->id_kota)->get(),
            'kecamatan'=>District::where('id',auth()->user()->id_kecamatan)->get(),
            'desa'=>Village::where('id',auth()->user()->id_desa)->get(),
        ]);
    }

    public function kurasi(){
        return view('pages.kurasi.view',[
            'title'=>'Kurasi IKM'
        ]);
    }

    public function searchIkm(Request $request)
    {
        $keyword = $request->query('q');

    $results = Ikm::with('kategori')
        ->where('nama', 'like', "%{$keyword}%")
        ->orWhere('jenisProduk', 'like', "%{$keyword}%")
        ->get()
        ->map(function ($item) {
            $item->encrypted_id = encrypt($item->id);
            return $item;
        });

    return response()->json($results);
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();
        $id_user = $request->input('id_user');

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required|string|min:8',
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'new_password.required' => 'Kata sandi baru wajib diisi.',
            'new_password.min' => 'Kata sandi baru minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'new_password_confirmation.required' => 'Konfirmasi kata sandi wajib diisi.',
        ]);

        if ($validator->fails()) {
            // Return JSON for AJAX, redirect for traditional form
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal.',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Gagal memperbarui kata sandi.');
        }

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kata sandi saat ini tidak cocok.',
                    'errors' => ['current_password' => ['Kata sandi saat ini tidak cocok.']]
                ], 422);
            }
            return redirect()->back()
                ->with('error', 'Kata sandi saat ini tidak cocok.')
                ->withInput();
        }

        User::where('id', $id_user)->update([
            'password' => Hash::make($request->new_password),
        ]);

        // Create notification for password change
        $this->createNotification(NotificationType::PASSWORD_CHANGED, [
            'message' => 'Kata sandi Anda telah diubah'
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kata sandi berhasil diperbarui.'
            ], 200);
        }

        return redirect()->route('profile.index')
            ->with('success', 'Kata sandi berhasil diperbarui.')
            ->with('UpdateBerhasil', 'Kata sandi telah diperbarui.');
    }
}
