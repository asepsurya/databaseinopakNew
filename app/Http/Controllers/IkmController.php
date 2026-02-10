<?php

namespace App\Http\Controllers;
use App\Model;
use App\Models\ikm;
use App\Models\Project;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use Illuminate\Http\Request;
use App\Http\Requests\StoreikmRequest;
use App\Http\Requests\UpdateikmRequest;

class IkmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(project $project)    
    {
        return view('pages.ikm.show',[
            'title'=>'Form Brainstorming',
            'project'=>$project,
            'dataIkm'=>ikm::where('id_Project',$project->id)->get(),
            'provinsi'=>Province::all(),
            'searchIkm'=>ikm::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreikmRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreikmRequest $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ikm  $ikm
     * @return \Illuminate\Http\Response
     */
    public function show(ikm $ikm)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ikm  $ikm
     * @return \Illuminate\Http\Response
     */
    public function edit(ikm $ikm)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateikmRequest  $request
     * @param  \App\Models\ikm  $ikm
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateikmRequest $request, ikm $ikm)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ikm  $ikm
     * @return \Illuminate\Http\Response
     */
    public function destroy(ikm $ikm)
    {
        //
    }
    
    public function tambahIkm(request $request){
        ikm::create([
            'nama'=>$request->nama,
            'jenisProduk'=>$request->jenisProduk,
            'id_Project'=>$request->id_Project
        ]);
        $request->session()->flash('Berhasil', 'Data Berhasil Disimpan');
        return redirect('/project/dataikm/'.$request->id_Project);
    }
       

    public function createIkm(request $request){
     
       $validasiData = $request->validate([
        'nama'=>'required|max:255',
        'gender'=>'required',
        'alamat'=>'required',
        'id_provinsi'=>'required',
        'id_kota'=>'required',
        'id_kecamatan'=>'required',
        'id_desa'=>'required',
        'rt'=>'required',
        'rw'=>'required',
        'telp'=>'required',
        // produk
        'jenisProduk'=>'required',
        'merk'=>'required',
        'tagline'=>'',
        'kelebihan'=>'required',
        'gramasi'=>'required',
        'jenisKemasan'=>'',
        'segmentasi'=>'required',
        'harga'=>'required',
        'varian'=>'required',
        'komposisi'=>'required',
        'redaksi'=>'',
        'other'=>'',
        // perizinan
        'namaUsaha'=>'required',
        'noNIB'=>'',
        'noISO'=>'',
        'noPIRT'=>'',
        'noHAKI'=>'',
        'noLayakSehat'=>'',
        'noHalal'=>'',
        'CPPOB'=>'',
        'HACCP'=>'',
        'legalitasLain'=>'',
        'id_Project'=>'',
        'gambar'=>'',
        
       ]);
                
       ikm::create($validasiData);
       $request->session()->flash('Berhasil', 'Data Berhasil Disimpan');
       return redirect('/project/dataikm/'.$request->id_Project);
    }

    public function UpdateIkm(request $request){
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
            ikm::where('id',$request->id_ikm)->update($validasiData);
            $request->session()->flash('UpdateBerhasil', 'Data Berhasil Diubah');
            $idikm = encrypt($request->id_ikm);
            return redirect('project/ikms/'.$idikm.'/'.$request->id_Project);
    }
    public function deleteIkm(request $request){
        ikm::destroy($request->id_ikm);
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
        $id_IKM = $request->getId_IKM;
        return view('pages.ikm.update',[
            'title'=>'Update IKM',
            'project'=>Project::Firstwhere('id',$id_project),
            'dataIkm'=>ikm::where('id',$id_IKM)->get(),
            'provinsi'=>Province::all(),
            'searchIkm'=>ikm::all(),
        
        ]);
        
    }
}
