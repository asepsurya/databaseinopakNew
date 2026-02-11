<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ikm;
use App\Models\DokumentasiCots;
use App\Models\BencmarkProduk;
use App\Models\ProdukDesign;
use App\Models\cots;
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

    public function index($id_ikm, $id_project){
        // unkripsi id ikm
        $id_ikm =decrypt($id_ikm);
        return view('pages.ikm.detile',[
            'title'=>'Detile IKM',
            'project'=>Project::Firstwhere('id',$id_project),
            'ikm'=>ikm::with(['province','district','village','regency','bencmark'])->where('id',$id_ikm)->get(),
            'dokumentasicots'=>DokumentasiCots::where('id_ikm',$id_ikm)->get(),
            'dokumentasicotscek'=>DokumentasiCots::where('id_ikm',$id_ikm)->count(),
            'cots'=>cots::where('id_ikm',$id_ikm)->count(),
            'cotsview'=>cots::where('id_ikm',$id_ikm)->get(),
            'searchIkm'=>ikm::all()
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
            $id_ikm = Crypt::decryptString($encryptedIkm);
            $id_project = Crypt::decryptString($encryptedProject);

            Log::info('Decrypt IDs: Success', [
                'encrypted_ikm' => $encryptedIkm,
                'encrypted_project' => $encryptedProject,
                'decrypted_ikm' => $id_ikm,
                'decrypted_project' => $id_project
            ]);

            // Redirect to the detail page with decrypted IDs
            return redirect()->route('detail', [
                'id_ikm' => $id_ikm,
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
            $id_ikm = Crypt::decryptString($encrypted_id);
            $id_project = Crypt::decryptString($encrypted_project);

            Log::info('Encrypted route access', [
                'encrypted_id' => $encrypted_id,
                'encrypted_project' => $encrypted_project,
                'decrypted_ikm' => $id_ikm,
                'decrypted_project' => $id_project
            ]);

            return view('pages.ikm.detile',[
                'title'=>'Detile IKM',
                'project'=>Project::Firstwhere('id',$id_project),
                'ikm'=>ikm::with(['province','district','village','regency','bencmark'])->where('id',$id_ikm)->get(),
                'dokumentasicots'=>DokumentasiCots::where('id_ikm',$id_ikm)->get(),
                'dokumentasicotscek'=>DokumentasiCots::where('id_ikm',$id_ikm)->count(),
                'cots'=>cots::where('id_ikm',$id_ikm)->count(),
                'cotsview'=>cots::where('id_ikm',$id_ikm)->get(),
                'searchIkm'=>ikm::all()
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
    public function ubahFotoIkm(request $request){

        // return $request->file('gambar')->store('ikms-img-Profile');

        $validasiGambar = [
            'gambar'=>'image|file',
        ];

        // Check if cropped image data is provided (base64)
        if($request->croppedImage) {
            // Handle base64 cropped image
            $request->validate([
                'croppedImage' => 'string',
            ]);

            $imageData = $request->croppedImage;

            // Remove data URL prefix if present
            if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $matches)) {
                $imageType = $matches[1];
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
            }

            $imageData = base64_decode($imageData);

            // Generate unique filename
            $fileName = 'ikm_' . $request->id_ikm . '_' . time() . '.jpg';
            $filePath = 'ikms-img-Profile/' . $fileName;

            // Store the image
            Storage::put($filePath, $imageData);

            // Delete old image if exists
            if($request->oldImage){
                Storage::delete($request->oldImage);
            }

            ikm::where('id', $request->id_ikm)->update(['gambar' => $filePath]);

            $request->session()->flash('UpdateBerhasil', 'Photo Berhasil Diubah');
            return redirect('/project/dataikm/'.$request->id_projek);
        }

        // Handle regular file upload
        $request->validate($validasiGambar);

        if($request->file('gambar')){
            //gambar dibah maka gambar di storage di hapus
            if($request->oldImage){
                Storage::delete($request->oldImage);
            }
            // post-images adalah directory penyimpanan Gambar
            $validasiGambar['gambar']=$request->file('gambar')->store('ikms-img-Profile');
        }

        ikm::where('id',$request->id_ikm)->update($validasiGambar);
        $request->session()->flash('UpdateBerhasil', 'Photo Berhasil Diubah');
        return redirect('/project/dataikm/'.$request->id_projek);
    }
    public function bencmark(request $request){
        $validasiGambar = $request->validate([
            'id_ikm'=>'',
            'gambar.*'=>'image|file',
            'id_Project'=>'',

        ]);

        foreach ($request->file('gambar') as $item){
            $validasiGambar['gambar']= $item->store('Bencmark-design');
            BencmarkProduk::create($validasiGambar);
        }

        $request->session()->flash('Berhasil', 'Benckmark Berhasil Ditambahkan');
        return redirect()->back();
    }

    public function cots(request $request){
        $validasi = $request->validate([
            'id_ikm'=>'required|unique:cots',
            'id_project'=>'required',
            // 'sejarahSingkat'=>'required',
            // 'produkjual'=>'required',
            // 'carapemasaran'=>'required',
            // 'bahanbaku'=>'required',
            // 'prosesproduksi'=>'required',
            // 'omset'=>'required',
            // 'kapasitasProduksi'=>'required',
            // 'kendala'=>'required',
            // 'solusi'=>'required',
        ]);
        cots::create($validasi);
        $request->session()->flash('Berhasil', 'Data Berhasil ditambahkan');
        return redirect()->back();
        // return redirect()->route('detail',[
        //     'id_ikm'=>encrypt($request->id_ikm),
        //     'id_project'=>$request->id_project
        // ]);
    }
    public function Updatecots(request $request){

        $validasi = $request->validate([
            'id_ikm'=>'',
            'id_project'=>'',
            'sejarahSingkat'=>'',
            'produkjual'=>'',
            'carapemasaran'=>'',
            'bahanbaku'=>'',
            'prosesproduksi'=>'',
            'omset'=>'',
            'kapasitasProduksi'=>'',
            'kendala'=>'',
            'solusi'=>'',
        ]);

        cots::where('id_ikm',$request->id_ikm)->update($validasi);
        $request->session()->flash('UpdateBerhasil', 'Data Berhasil Diubah');
        return redirect()->route('detail',[
            'id_ikm'=>encrypt($request->id_ikm),
            'id_project'=>$request->id_project
        ]);
    }
    // input image Multiple
    public function dokumentasi(request $request){
        $validasi = $request->validate([
            'id_ikm'=>'',
            'id_project'=>'',
            'gambar.*'=>''
          ]);
        $images=[];
        foreach ($request->file('gambar') as $item){
            $validasi['gambar']= $item->store('images');
            DokumentasiCots::create($validasi);
        }

       $request->session()->flash('Berhasil', 'Data Berhasil ditambahkan');
        return redirect()->back();

    }

   public function deleteDoc(request $request){
     $images = DokumentasiCots::all();
     $id_ikm = $request->id_ikm;
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
    $validasiGambar = $request->validate([
        'id_ikm'=>'',
        'id_project'=>'',
        'gambar.*'=>'file|image'
    ]);

    foreach ($request->file('gambar') as $item){
        $validasiGambar['gambar']= $item->store('Produk-design');
        ProdukDesign::create($validasiGambar);
    }

    $request->session()->flash('Berhasil', 'Data Berhasil disimpan');
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
    ikm::where(['id'=>$request->id_ikm,'id_Project'=>$request->id_Project])->update([
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
        'dataIkm'=>ikm::all(),
        'project'=>Project::all(),
        'provinsi'=>province::all(),

    ]);
   }
   public function cots_save(Request $request){

    if($request->file('gambar')){
        $validasiGambar = $request->file('gambar')->store('ikms-img-Profile');
    }

    ikm::create([
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
    $a = ikm::where(['nama'=>$request->nama,'telp'=>$request->telp,'id_Project'=>$request->id_Project])->get();
    foreach($a as $data){
        $id_ikm = $data->id;
        $id_project=$data->id_Project;
    }
    // input Dokumentasi COTS
    foreach ($request->file('gambargallery') as $item){
        $validasiGambar2 = $item->store('images');
        DokumentasiCots::create([
            'id_ikm'=>$id_ikm,
            'id_project'=>$id_project,
            'gambar'=>$validasiGambar2
        ]);
    }

    // input Data COTS
    cots::create([
        'id_ikm'=>$id_ikm,
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
