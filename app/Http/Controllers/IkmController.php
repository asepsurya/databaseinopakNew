<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Ikm;
use App\Models\Project;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Village;
use Illuminate\Http\Request;

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
    public function deleteIkm(request $request){
        Ikm::destroy($request->id_Ikm);
        $request->session()->flash('HapusBerhasil', 'Data Berhasil dihapus');
        return redirect('/project/dataikm/'.$request->id_Project);
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
