<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ikm;
use App\Models\DokumentasiCots;
use App\Models\BencmarkProduk;
use App\Models\ProdukDesign;
use App\Models\Cots;
use App\Models\Project;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class DetileIkmController extends Controller
{

    public function index($id_Ikm, $id_project){
        // Decripsi id ikm
        $id_Ikm = decrypt($id_Ikm);

        // OPTIMIZED: Use first() instead of get() for single record
        $project = Project::find($id_project);

        // OPTIMIZED: Eager load ALL relationships to prevent N+1 queries
        $ikmData = Ikm::with([
            'province',
            'district',
            'village',
            'regency',
            'bencmark',
            'produkDesign',
            'cots'
        ])->where('id', $id_Ikm)->first();

        // OPTIMIZED: Get related data only once, use collection methods instead of redundant queries
        $dokumentasicots = DokumentasiCots::where('id_Ikm', $id_Ikm)->get();
        $dokumentasicotscek = $dokumentasicots->count(); // Reuse collection instead of separate query

        $cotsData = Cots::where('id_Ikm', $id_Ikm)->get();
        $cotsCount = $cotsData->count(); // Reuse collection instead of separate query

        // OPTIMIZED: REMOVED Ikm::all() - This was loading ALL IKM records!
        // If searchIkm is needed, consider pagination or limiting results
        // $searchIkm = Ikm::all() // REMOVED - Major performance issue!

        return view('pages.ikm.detile',[
            'title'=>'Detile IKM',
            'project'=>$project,
            'Ikm'=>collect([$ikmData]), // Keep as collection for backward compatibility
            'dokumentasiCots'=>$dokumentasicots,
            'dokumentasiCotscek'=>$dokumentasicotscek,
            'cots'=>$cotsCount,
            'cotsview'=>$cotsData,
            // 'searchIkm'=>$searchIkm // REMOVED - Causes slow loading
        ]);
    }

    /**
     * Handle encrypted ID click from JavaScript
     * Decrypts IDs and redirects to the detail page
     */
    public function decryptIds(Request $request)
    {
        try {
            $encryptedIkm = $request->get('encrypted_ikm');
            $encryptedProject = $request->get('encrypted_project');

            if (!$encryptedIkm || !$encryptedProject) {
                Log::warning('Decrypt IDs: Missing encrypted parameters', [
                    'encrypted_ikm' => $encryptedIkm,
                    'encrypted_project' => $encryptedProject
                ]);

                return redirect()->back()->with('error', 'Parameter tidak lengkap');
            }

            // Decrypt the IDs
            $id_Ikm = Crypt::decryptString($encryptedIkm);
            $id_project = Crypt::decryptString($encryptedProject);

            Log::info('Decrypt IDs: Success', [
                'encrypted_ikm' => $encryptedIkm,
                'encrypted_project' => $encryptedProject,
                'decrypted_ikm' => $id_Ikm,
                'decrypted_project' => $id_project
            ]);

            // Redirect to the detail page with decrypted IDs
            return redirect()->route('detail', [
                'id_Ikm' => $id_Ikm,
                'id_project' => $id_project
            ]);

        } catch (\Exception $e) {
            Log::error('Decrypt IDs failed: ' . $e->getMessage(), [
                'encrypted_ikm' => $request->get('encrypted_ikm'),
                'encrypted_project' => $request->get('encrypted_project'),
                'exception' => $e
            ]);

            return redirect()->back()->with('error', 'Gagal mendekripsi ID. Silakan coba lagi.');
        }
    }

    /**
     * Direct encrypted route - accepts already encrypted IDs in URL
     * Example: /e/ikm/{encrypted_id}/{encrypted_project}
     */
    public function encryptedIndex($encrypted_id, $encrypted_project)
    {
        try {
            // Decrypt the IDs
            $id_Ikm = Crypt::decryptString($encrypted_id);
            $id_project = Crypt::decryptString($encrypted_project);

            Log::info('Encrypted route access', [
                'encrypted_id' => $encrypted_id,
                'encrypted_project' => $encrypted_project,
                'decrypted_ikm' => $id_Ikm,
                'decrypted_project' => $id_project
            ]);

            // OPTIMIZED: Use find() instead of Firstwhere() for single record
            $project = Project::find($id_project);

            // OPTIMIZED: Eager load ALL relationships to prevent N+1 queries
            $ikmData = Ikm::with([
                'province',
                'district',
                'village',
                'regency',
                'bencmark',
                'produkDesign',
                'cots'
            ])->where('id', $id_Ikm)->first();

            // OPTIMIZED: Get related data only once
            $dokumentasicots = DokumentasiCots::where('id_Ikm', $id_Ikm)->get();
            $dokumentasicotscek = $dokumentasicots->count();

            $cotsData = Cots::where('id_Ikm', $id_Ikm)->get();
            $cotsCount = $cotsData->count();

            return view('pages.ikm.detile',[
                'title'=>'Detile IKM',
                'project'=>$project,
                'Ikm'=>collect([$ikmData]),
                'dokumentasiCots'=>$dokumentasicots,
                'dokumentasiCotscek'=>$dokumentasicotscek,
                'cots'=>$cotsCount,
                'cotsview'=>$cotsData
            ]);

        } catch (\Exception $e) {
            Log::error('Encrypted route decryption failed: ' . $e->getMessage(), [
                'encrypted_id' => $encrypted_id,
                'encrypted_project' => $encrypted_project,
                'exception' => $e
            ]);

            return redirect()->route('dashboard')->with('error', 'ID tidak valid atau sudah kadaluarsa');
        }
    }
    public function ubahFotoIkm(Request $request){
        $validated = $request->validate([
            'id_Ikm' => 'required|string',
            'id_projek' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'croppedImage' => 'nullable|string',
            'oldImage' => 'nullable|string',
        ]);

        $filePath = null;

        // Handle cropped image (base64)
        if ($request->croppedImage) {
            $imageData = $request->croppedImage;

            // Remove data URL prefix if present
            if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $matches)) {
                $imageType = $matches[1];
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
            }

            $imageData = base64_decode($imageData);

            // Generate unique filename
            $fileName = 'ikm_' . $validated['id_Ikm'] . '_' . time() . '.jpg';
            $filePath = 'ikms-img-Profile/' . $fileName;

            // Store the image
            Storage::put($filePath, $imageData);

            // Delete old image if exists
            if ($request->oldImage) {
                Storage::delete($request->oldImage);
            }
        }
        // Handle regular file upload
        elseif ($request->file('gambar')) {
            // Delete old image if exists
            if ($request->oldImage) {
                Storage::delete($request->oldImage);
            }
            $filePath = $request->file('gambar')->store('ikms-img-Profile');
        }

        if ($filePath) {
            Ikm::where('id', $validated['id_Ikm'])->update(['gambar' => $filePath]);
        }

        $request->session()->flash('UpdateBerhasil', 'Photo Berhasil Diubah');
        return redirect()->route('detail.encrypted', [
            'encrypted_id' => encryptId($validated['id_Ikm']),
            'encrypted_project' => encryptId($validated['id_projek'])
        ]);
    }
    /**
     * Auto-save brainstorming data via AJAX
     * Saves form data without requiring manual submission
     */
    public function autoSaveBrainstorming(Request $request)
    {
        try {
            $validated = $request->validate([
                'id_Ikm' => 'required|exists:ikms,id',
                'id_Project' => 'required|exists:projects,id',
            ]);

            // Get all brainstorming fields
            $fields = [
                'jenisProduk', 'merk', 'komposisi', 'varian', 'kelebihan',
                'namaUsaha', 'noPIRT', 'noHalal', 'legalitasLain', 'other',
                'segmentasi', 'jenisKemasan', 'harga', 'tagline', 'redaksi', 'gramasi'
            ];

            $updateData = [];
            foreach ($fields as $field) {
                if ($request->has($field)) {
                    $updateData[$field] = $request->input($field);
                }
            }

            if (!empty($updateData)) {
                $ikm = Ikm::where('id', $request->id_Ikm)->first();
                if ($ikm) {
                    $ikm->update($updateData);

                    Log::info('Auto-save brainstorming successful', [
                        'ikm_id' => $request->id_Ikm,
                        'project_id' => $request->id_Project,
                        'fields_updated' => array_keys($updateData),
                        'saved_at' => now()->toISOString()
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Data berhasil disimpan',
                        'saved_at' => now()->format('H:i:s'),
                        'fields_updated' => count($updateData)
                    ], 200);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Tidak ada perubahan untuk disimpan',
                'saved_at' => now()->format('H:i:s')
            ], 200);

        } catch (\Exception $e) {
            Log::error('Auto-save brainstorming failed: ' . $e->getMessage(), [
                'ikm_id' => $request->id_Ikm ?? null,
                'project_id' => $request->id_Project ?? null,
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data. Silakan coba lagi atau hubungi administrator.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    /**
     * Auto-save COTS data via AJAX
     * Saves form data without requiring manual submission
     */
    public function autoSaveCots(Request $request)
    {
        try {
            $validated = $request->validate([
                'id_Ikm' => 'required|exists:ikms,id',
                'id_Project' => 'required|exists:projects,id',
                'id_Cots' => 'required|exists:cots,id',
            ]);

            // Get all COTS fields
            $fields = [
                'sejarahSingkat', 'produkjual', 'carapemasaran', 'bahanbaku',
                'prosesproduksi', 'omset', 'kapasitasProduksi', 'kendala', 'solusi'
            ];

            $updateData = [];
            foreach ($fields as $field) {
                if ($request->has($field)) {
                    $updateData[$field] = $request->input($field);
                }
            }

            if (!empty($updateData)) {
                $cots = Cots::where('id', $request->id_Cots)->first();
                if ($cots) {
                    $cots->update($updateData);

                    Log::info('Auto-save COTS successful', [
                        'cots_id' => $request->id_Cots,
                        'ikm_id' => $request->id_Ikm,
                        'project_id' => $request->id_Project,
                        'fields_updated' => array_keys($updateData),
                        'saved_at' => now()->toISOString()
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Data berhasil disimpan',
                        'saved_at' => now()->format('H:i:s'),
                        'fields_updated' => count($updateData)
                    ], 200);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Tidak ada perubahan untuk disimpan',
                'saved_at' => now()->format('H:i:s')
            ], 200);

        } catch (\Exception $e) {
            Log::error('Auto-save COTS failed: ' . $e->getMessage(), [
                'ikm_id' => $request->id_Ikm ?? null,
                'project_id' => $request->id_Project ?? null,
                'cots_id' => $request->id_Cots ?? null,
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data. Silakan coba lagi atau hubungi administrator.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function bencmark(Request $request){
        // Validate input with proper rules
        $validated = $request->validate([
            'id_Ikm' => 'required|string',
            'id_Project' => 'required|string',
            'gambar.*' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        // Check if files were uploaded
        if (!$request->hasFile('gambar')) {
            $request->session()->flash('Gagal', 'Tidak ada gambar yang dipilih');
            return redirect()->back();
        }

        // Process each uploaded file
        $uploadedCount = 0;
        foreach ($request->file('gambar') as $file){
            $filePath = $file->store('Bencmark-design');

            BencmarkProduk::create([
                'id_Ikm' => $validated['id_Ikm'],
                'id_Project' => $validated['id_Project'],
                'gambar' => $filePath
            ]);
            $uploadedCount++;
        }

        $request->session()->flash('Berhasil', "$uploadedCount Benchmark Berhasil Ditambahkan");
        return redirect()->back();
    }

    public function cots(Request $request){

        // Check if COTS already exists for this IKM
        $existingCots = Cots::where('id_Ikm', $request->id_Ikm)->first();
        if ($existingCots) {
            $request->session()->flash('Error', 'Data COTS untuk IKM ini sudah ada!');
            return redirect()->back();
        }

        $validated = $request->validate([
            'id_Ikm' => 'required|string|unique:cots,id_Ikm',
            'id_Project' => 'required|string',
            'sejarahSingkat' => 'nullable|string',
            'produkjual' => 'nullable|string',
            'carapemasaran' => 'nullable|string',
            'bahanbaku' => 'nullable|string',
            'prosesproduksi' => 'nullable|string',
            'omset' => 'nullable|string',
            'kapasitasProduksi' => 'nullable|string',
            'kendala' => 'nullable|string',
            'solusi' => 'nullable|string',
        ]);

        Cots::create([
            'id_Ikm' => $validated['id_Ikm'],
            'id_Project' => $validated['id_Project'],
            'sejarahSingkat' => $validated['sejarahSingkat'] ?? null,
            'produkjual' => $validated['produkjual'] ?? null,
            'carapemasaran' => $validated['carapemasaran'] ?? null,
            'bahanbaku' => $validated['bahanbaku'] ?? null,
            'prosesproduksi' => $validated['prosesproduksi'] ?? null,
            'omset' => $validated['omset'] ?? null,
            'kapasitasProduksi' => $validated['kapasitasProduksi'] ?? null,
            'kendala' => $validated['kendala'] ?? null,
            'solusi' => $validated['solusi'] ?? null,
        ]);

        $request->session()->flash('Berhasil', 'Data COTS Berhasil ditambahkan');
        return redirect()->back();
    }
    public function Updatecots(Request $request){
        $validated = $request->validate([
            'id_Ikm' => 'required|string',
            'id_Project' => 'required|string',
            'id_Cots' => 'nullable|string',
            'sejarahSingkat' => 'nullable|string',
            'produkjual' => 'nullable|string',
            'carapemasaran' => 'nullable|string',
            'bahanbaku' => 'nullable|string',
            'prosesproduksi' => 'nullable|string',
            'omset' => 'nullable|string',
            'kapasitasProduksi' => 'nullable|string',
            'kendala' => 'nullable|string',
            'solusi' => 'nullable|string',
        ]);

        // Update data except id_Cots
        $updateData = $validated;
        unset($updateData['id_Cots']);

        Cots::where('id', $validated['id_Cots'])->update($updateData);

        // Check if AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data COTS Berhasil Diubah'
            ]);
        }

        $request->session()->flash('UpdateBerhasil', 'Data COTS Berhasil Diubah');
        return redirect()->route('detail',[
            'id_Ikm' => encrypt($validated['id_Ikm']),
            'id_project' => $validated['id_Project']
        ])->with('tab', 'tab-Cots');
    }
    // Upload multiple documentation images
    public function dokumentasi(Request $request){
        $validated = $request->validate([
            'id_Ikm' => 'required|string',
            'id_project' => 'required|string',
            'gambar.*' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        if (!$request->hasFile('gambar')) {
            $request->session()->flash('Gagal', 'Tidak ada gambar yang dipilih');
            return redirect()->back();
        }

        $uploadedCount = 0;
        foreach ($request->file('gambar') as $file) {
            $filePath = $file->store('images');

            DokumentasiCots::create([
                'id_Ikm' => $validated['id_Ikm'],
                'id_project' => $validated['id_project'],
                'gambar' => $filePath
            ]);
            $uploadedCount++;
        }

        $request->session()->flash('Berhasil', "$uploadedCount Dokumentasi Berhasil Ditambahkan");
        return redirect()->back();
    }

   public function deleteDoc(request $request){
     $images = DokumentasiCots::all();
     $id_Ikm = $request->id_Ikm;
     $id_gambar=$request->id_gambar;
     $old_gambar=$request->old_gambar;

    //  hapus di storage
    if($old_gambar){
        Storage::delete($old_gambar);
    }
     DokumentasiCots::where('gambar',$old_gambar)->orWhere('id',$id_gambar)->delete();
     $request->session()->flash('Berhasil', 'Data Berhasil DiHapus');
     return redirect()->back();


   }
   public function bencmarkDelete(Request $request, $id_gambar){
    $old_gambar = $request->oldImage;
    if($old_gambar){
        Storage::delete($old_gambar);
    }
     BencmarkProduk::where('id',$id_gambar)->delete();
     $request->session()->flash('HapusBerhasil', 'Data Berhasil diHapus');
     return redirect()->back();
   }

   public function tambahDesain(Request $request, $id){
    $validated = $request->validate([
        'id_Ikm' => 'required|string',
        'id_project' => 'required|string',
        'gambar.*' => 'required|image|mimes:jpeg,png,jpg,gif',
    ]);

    if (!$request->hasFile('gambar')) {
        $request->session()->flash('Gagal', 'Tidak ada gambar yang dipilih');
        return redirect()->back();
    }

    $uploadedCount = 0;
    foreach ($request->file('gambar') as $file) {
        $filePath = $file->store('Produk-design');

        ProdukDesign::create([
            'id_Ikm' => $validated['id_Ikm'],
            'id_project' => $validated['id_project'],
            'gambar' => $filePath
        ]);
        $uploadedCount++;
    }

    $request->session()->flash('Berhasil', "$uploadedCount Desain Berhasil Ditambahkan");
    return redirect()->back();
   }

   public function deleteDesain(request $request , $id){
    $old_gambar = $request->oldImage;
    if($old_gambar){
        Storage::delete($old_gambar);
    }
    ProdukDesign::where('id',$id)->delete();
    $request->session()->flash('HapusBerhasil', 'Data Berhasil diHapus');
    return redirect()->back();
   }

   public function updateBrainstorming(request $request){
    Ikm::where(['id'=>$request->id_Ikm,'id_Project'=>$request->id_Project])->update([
        'jenisProduk'=>$request->jenisProduk,
        'merk'=>$request->merk,
        'komposisi'=>$request->komposisi,
        'varian'=>$request->varian,
        'kelebihan'=>$request->kelebihan,
        'namaUsaha'=>$request->namaUsaha,
        'noPIRT'=>$request->noPIRT,
        'noHalal'=>$request->noHalal,
        'legalitasLain'=>$request->legalitasLain,
        'other'=>$request->other,
        'segmentasi'=>$request->segmentasi,
        'jenisKemasan'=>$request->jenisKemasan,
        'harga'=>$request->harga,
        'tagline'=>$request->tagline,
        'redaksi'=>$request->redaksi,
        'gramasi'=>$request->gramasi

    ]);
    $request->session()->flash('Berhasil', 'Data Berhasil disimpan');
    return redirect()->back();

   }

    public function publik_cots(){
    return view('pages.public-cots.cots_public',[
        'title'=>'Form COTS',
        'dataIkm'=>Ikm::all(),
        'project'=>Project::all(),
        'provinsi'=>province::all(),

    ]);
   }
   public function cots_save(Request $request){

    if($request->file('gambar')){
        $validasiGambar = $request->file('gambar')->store('ikms-img-Profile');
    }

    Ikm::create([
        'nama'=>$request->nama,
        'telp'=>$request->telp,
        'gender'=>$request->gender,
        'id_Project'=>$request->id_Project,
        'alamat'=>$request->alamat,
        'id_provinsi'=>$request->id_provinsi,
        'id_kota'=>$request->id_kota,
        'id_kecamatan'=>$request->id_kecamatan,
        'id_desa'=>$request->id_desa,
        'rt'=>$request->rt,
        'rw'=>$request->rw,
        'gambar'=>$validasiGambar

    ]);

    // get data
    $a = Ikm::where(['nama'=>$request->nama,'telp'=>$request->telp,'id_Project'=>$request->id_Project])->get();
    foreach($a as $data){
        $id_Ikm = $data->id;
        $id_project=$data->id_Project;
    }
    // input Dokumentasi COTS
    foreach ($request->file('gambargallery') as $item){
        $validasiGambar2 = $item->store('images');
        DokumentasiCots::create([
            'id_Ikm'=>$id_Ikm,
            'id_project'=>$id_project,
            'gambar'=>$validasiGambar2
        ]);
    }

    // input Data COTS
    Cots::create([
        'id_Ikm'=>$id_Ikm,
        'id_project'=>$id_project,
        'sejarahSingkat'=>$request->sejarahSingkat,
        'produkjual'=>$request->produkjual,
        'carapemasaran'=>$request->carapemasaran,
        'bahanbaku'=>$request->bahanbaku,
        'prosesproduksi'=>$request->prosesproduksi,
        'omset'=>$request->omset,
        'kapasitasProduksi'=>$request->kapasitasProduksi,
        'kendala'=>$request->kendala,
        'solusi'=>$request->solusi,

    ]);
    return view('pages.public-cots.finish_cots',[
        'title'=>'Finish'
    ]);
   }

}
