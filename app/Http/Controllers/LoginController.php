<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ikm;

class LoginController extends Controller
{
    public function login(Request $request){
        $cek = $request->validate([
            'email'=>'required|email',
            'password'=>'required|min:6'
        ]);

        if(Auth::attempt($cek)){
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }
        //jika login error
        return back()->with('loginError','Login Gagal!! Periksa Kembali Data Anda');

    }
    public function dashboard(){
        return view('pages.dashboard.index',[
            'title'=>'Dashboard',
            'searchIkm'=>ikm::all()
        ]);
    }
    public function index(){
        return view('auth.login');
    }
    Public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
