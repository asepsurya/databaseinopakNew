<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Ikm;
use App\Models\Project;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Village;
use App\Models\BencmarkProduk;
use App\Models\ProdukDesign;
use App\Models\Cots;
use App\Models\DokumentasiCots;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ThumbnailHelper;

class IkmController extends Controller
{
    public function view(project $project)
    {
        return view('pages.ikm.show',[
            'title'=>'Form Brainstorming',
            'project'=>$project,
            'dataIkm'=>Ikm::where('id_Project',$project->id)->get(),
            'provinsi'=>Province::all(),
            'searchIkm'=>Ikm::all()
        ]);
    }
       public function tambahIkm(Request $request){
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jenisProduk' => 'required|string',
            'id_Project' => 'required|string',
        ]);

        Ikm::create([
            'nama' => $validated['nama'],
            'jenisProduk' => $validated['jenisProduk'],
            'id_Project' => $validated['id_Project']
        ]);

        $request->session()->flash('Berhasil', 'Data IKM Berhasil Disimpan');
        return redirect('/project/dataIkm/'.$validated['id_Project']);
    }
    public function createIkm(Request $request){
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'gender' => 'required|string',
            'alamat' => 'required|string',
            'id_provinsi' => 'required|string',
            'id_kota' => 'required|string',
            'id_kecamatan' => 'required|string',
            'id_desa' => 'required|string',
            'rt' => 'required|string',
            'rw' => 'required|string',
            'telp' => 'required|string',
            // produk
            'jenisProduk' => 'required|string',
            'merk' => 'required|string',
            'tagline' => 'nullable|string',
            'kelebihan' => 'required|string',
            'gramasi' => 'required|string',
            'jenisKemasan' => 'nullable|string',
            'segmentasi' => 'required|string',
            'harga' => 'required|string',
            'varian' => 'required|string',
            'komposisi' => 'required|string',
            'redaksi' => 'nullable|string',
            'other' => 'nullable|string',
            // perizinan
            'namaUsaha' => 'required|string',
            'noNIB' => 'nullable|string',
            'noISO' => 'nullable|string',
            'noPIRT' => 'nullable|string',
            'noHAKI' => 'nullable|string',
            'noLayakSehat' => 'nullable|string',
            'noHalal' => 'nullable|string',
            'CPPOB' => 'nullable|string',
            'HACCP' => 'nullable|string',
            'legalitasLain' => 'nullable|string',
            'id_Project' => 'required|string',
            'gambar' => 'nullable|string',
        ]);

        Ikm::create($validatedData);
        $request->session()->flash('Berhasil', 'Data IKM Berhasil Disimpan');
        return redirect('/project/dataIkm/'.$validatedData['id_Project']);
    }

    public function UpdateIkm(request $request){
        // $idikm = encrypt($request->id_Ikm);
        // dd('project/ikms/'.$idikm.'/'.$request->id_Project);
        $validasiData = $request->validate([
            'nama'=>'',
            'gender'=>'',
            'alamat'=>'',
            'id_provinsi'=>'',
            'id_kota'=>'',
            'id_kecamatan'=>'',
            'id_desa'=>'',
            'rt'=>'',
            'rw'=>'',
            'telp'=>'',
            // produk
            'jenisProduk'=>'',
            'merk'=>'',
            'tagline'=>'',
            'kelebihan'=>'',
            'gramasi'=>'',
            'jenisKemasan'=>'',
            'segmentasi'=>'',
            'harga'=>'',
            'varian'=>'',
            'komposisi'=>'',
            'redaksi'=>'',
            'other'=>'',
            // perizinan
            'namaUsaha'=>'',
            // 'noNIB'=>'',
            // 'noISO'=>'',
            'noPIRT'=>'',
            // 'noHAKI'=>'',
            // 'noLayakSehat'=>'',
            'noHalal'=>'',
            // 'CPPOB'=>'',
            // 'HACCP'=>'',
            'legalitasLain'=>'',
            'id_Project'=>'',

           ]);
            Ikm::where('id',$request->id_Ikm)->update($validasiData);
            $request->session()->flash('UpdateBerhasil', 'Data Berhasil Diubah');
            $idikm = encrypt($request->id_Ikm);
           return redirect()->route('detail', [
                'id_Ikm'     => $idikm,
                'id_project' => $request->id_Project
            ]);
    }

        public function edit(Ikm $ikm)
        {
            return view('pages.ikm.update',[
                'title'=>'Update IKM',
                'project'=>Project::Firstwhere('id',$ikm->id_Project),
                'dataIkm'=>Ikm::where('id',$ikm->id)->get(),
                'provinsi'=>Province::all(),
                'searchIkm'=>Ikm::all(),

            ]);
        }
    public function deleteIkm(Request $request){
        // Validate the request
        $validated = $request->validate([
            'id_Ikm' => 'required|integer|exists:ikms,id',
            'id_Project' => 'required|integer|exists:projects,id',
        ]);

        $ikmId = $validated['id_Ikm'];
        $projectId = $validated['id_Project'];

        try {
            DB::beginTransaction();

            // Get the Ikm record first
            $ikm = Ikm::findOrFail($ikmId);

            // Delete related records first (to handle foreign key constraints)
            // Delete Bencmark Produk
            $bencmarks = BencmarkProduk::where('id_Ikm', $ikmId)->get();
            foreach ($bencmarks as $bencmark) {
                // Delete images if exists
                if ($bencmark->gambar) {
                    self::deleteImageFile($bencmark->gambar);
                }
                $bencmark->delete();
            }

            // Delete Produk Design
            $designs = ProdukDesign::where('id_Ikm', $ikmId)->get();
            foreach ($designs as $design) {
                // Delete images if exists
                if ($design->gambar) {
                    self::deleteImageFile($design->gambar);
                }
                $design->delete();
            }

            // Delete Cots and their Dokumentasi
            $cots = Cots::where('id_Ikm', $ikmId)->get();
            foreach ($cots as $cot) {
                // Delete dokumentasi related to this cots
                $dokumentasis = DokumentasiCots::where('id_Ikm', $cot->id)->get();
                foreach ($dokumentasis as $doc) {
                    if ($doc->gambar) {
                        self::deleteImageFile($doc->gambar);
                    }
                    $doc->delete();
                }
                // Delete cots image if exists
                if ($cot->gambar) {
                    self::deleteImageFile($cot->gambar);
                }
                $cot->delete();
            }

            // Delete main Ikm image if exists
            if ($ikm->gambar) {
                self::deleteImageFile($ikm->gambar);
            }

            // Delete the Ikm record
            $ikm->delete();

            DB::commit();

            // Flash success message
            $request->session()->flash('HapusBerhasil', 'Data IKM berhasil dihapus beserta semua file terkait.');
            return redirect('/project/dataIkm/' . $projectId);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting IKM: ' . $e->getMessage());
            $request->session()->flash('HapusGagal', 'Gagal menghapus data IKM: ' . $e->getMessage());
            return redirect('/project/dataIkm/' . $projectId)->withErrors(['error' => 'Gagal menghapus data IKM']);
        }
    }

    /**
     * AJAX delete Ikm - handles deletion via AJAX request
     */
    public function ajaxDeleteIkm(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'id_Ikm' => 'required|integer|exists:ikms,id',
            'id_Project' => 'required|integer|exists:projects,id',
        ]);

        $ikmId = $validated['id_Ikm'];
        $projectId = $validated['id_Project'];

        try {
            DB::beginTransaction();

            // Get the Ikm record first
            $ikm = Ikm::findOrFail($ikmId);

            // Delete related records first (to handle foreign key constraints)
            // Delete Bencmark Produk
            $bencmarks = BencmarkProduk::where('id_Ikm', $ikmId)->get();
            foreach ($bencmarks as $bencmark) {
                if ($bencmark->gambar) {
                    self::deleteImageFile($bencmark->gambar);
                }
                $bencmark->delete();
            }

            // Delete Produk Design
            $designs = ProdukDesign::where('id_Ikm', $ikmId)->get();
            foreach ($designs as $design) {
                if ($design->gambar) {
                    self::deleteImageFile($design->gambar);
                }
                $design->delete();
            }

            // Delete Cots and their Dokumentasi
            $cots = Cots::where('id_Ikm', $ikmId)->get();
            foreach ($cots as $cot) {
                $dokumentasis = DokumentasiCots::where('id_Ikm', $cot->id)->get();
                foreach ($dokumentasis as $doc) {
                    if ($doc->gambar) {
                        self::deleteImageFile($doc->gambar);
                    }
                    $doc->delete();
                }
                if ($cot->gambar) {
                    self::deleteImageFile($cot->gambar);
                }
                $cot->delete();
            }

            // Delete main Ikm image if exists
            if ($ikm->gambar) {
                self::deleteImageFile($ikm->gambar);
            }

            // Delete the Ikm record
            $ikm->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data IKM berhasil dihapus beserta semua file terkait.',
                'redirect' => '/project/dataIkm/' . $projectId
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting IKM via AJAX: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data IKM: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper function to delete image files from storage
     */
    private static function deleteImageFile(string $imagePath): bool
    {
        try {
            // Delete thumbnails first
            if (class_exists('App\Helpers\ThumbnailHelper')) {
                \App\Helpers\ThumbnailHelper::deleteThumbnails($imagePath);
            }

            // Delete the original file
            $fullPath = storage_path('app/public/' . $imagePath);
            if (file_exists($fullPath)) {
                return unlink($fullPath);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Error deleting image file: ' . $e->getMessage());
            return false;
        }
    }

    public function getkabupaten(request $request){
        $id_provinsi = $request->id_provinsi;

        $option = "<option value=''> Kota/Kabupaten </option>";
        $kabupatens = Regency::where('province_id',$id_provinsi)->get();
        foreach($kabupatens as $kabupaten){
            $option.="<option value='$kabupaten->id'> $kabupaten->name </option>";
        }
        echo $option;
    }
    public function getkecamatan(request $request){
        $id_kabupaten = $request->id_kabupaten;

        $option = "<option value=''> Kecamatan </option>";
        $kecamatans = District::where('regency_id',$id_kabupaten)->get();
        foreach($kecamatans as $kecamatan){
            $option.="<option value='$kecamatan->id'> $kecamatan->name </option>";
        }
        echo $option;
    }

    public function getdesa(request $request){
        $id_kecamatan = $request->id_kecamatan;

        $option = "<option value=''> Kelurahan/Desa </option>";
        $desas = Village::where('district_id',$id_kecamatan)->get();
        foreach($desas as $desa){
            $option.= "<option value='$desa->id'> $desa->name </option>";
        }
        echo $option;
    }
    public function getmemberUpdate(request $request){

        $id_project = $request->getId_project;
        $id_IKM = $request->getId_Ikm;
        return view('pages.ikm.update',[
            'title'=>'Update IKM',
            'project'=>Project::Firstwhere('id',$id_project),
            'dataIkm'=>ikm::where('id',$id_IKM)->get(),
            'provinsi'=>Province::all(),
            'searchIkm'=>ikm::all(),

        ]);

        return view('pages.ikm.update',[
            'title'=>'Update IKM',
            'project'=>Project::find($id_project),
            'dataIkm'=>Ikm::where('id',$id_Ikm)->get(),
            'Ikm'=>$ikm, // Add this for backward compatibility
            'provinsi'=>Province::all(),
            'searchIkm'=>Ikm::all(),
        ]);

    }

}
