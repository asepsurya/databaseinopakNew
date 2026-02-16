<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AppSetting;
use Illuminate\Http\Request;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Check if registration is enabled
        $registrationEnabled = AppSetting::get('registration_enabled', true);

        if (!$registrationEnabled) {
            return redirect('/login')->with(
                'loginError',
                'Pendaftaran pengguna baru telah dinonaktifkan. Silakan hubungi administrator untuk membuat akun.'
            );
        }

        // Get all data
        $provinces = Province::all();

        return view('authentication.register',[
            'provinsi'=> $provinces,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Double-check if registration is enabled
        $registrationEnabled = AppSetting::get('registration_enabled', true);

        if (!$registrationEnabled) {
            return redirect('/login')->with(
                'loginError',
                'Pendaftaran pengguna baru telah dinonaktifkan.'
            );
        }

        $validatedData = $request->validate([
            'nik'=>'required|min:16',
            'name'=>'required|max:255',
            'telp'=>'required|unique:users',
            'komunitas'=>'',
            'gender'=>'required',
            'alamat'=>'required',
            'id_provinsi'=>'required',
            'id_kota'=>'required',
            'id_kecamatan'=>'required',
            'id_desa'=>'required',
            'rt'=>'required',
            'rw'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|same:confirmPassword',
        ]);

        // enkripsi password using bcrypt
        $validatedData['password'] = Hash::make($validatedData['password']);

        User::create($validatedData);

        $request->session()->flash('Berhasil', 'Pendaftaran Berhasil, Silahkan Login pada Form yang tersedia');
        return redirect('/login');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
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

}
