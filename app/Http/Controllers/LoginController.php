<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ikm;
use App\Traits\CreatesNotifications;
use App\Services\NotificationService;

class LoginController extends Controller
{
    use CreatesNotifications;

    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
        $this->initializeNotificationService();
    }

    public function login(Request $request){
        $cek = $request->validate([
            'email'=>'required|email',
            'password'=>'required|min:6'
        ]);

        if(Auth::attempt($cek)){
            $request->session()->regenerate();

            // Create login notification
            $this->notifyLogin(true);

            // Initialize notification preferences for user
            $this->notificationService->initializePreferences(Auth::user());

            return redirect()->intended('/dashboard');
        }

        // Create failed login notification
        $this->notifyLogin(false);

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
        // Create logout notification
        Auth::check() && Auth::user() ?
            $this->createNotification(\App\Enums\NotificationType::LOGOUT, [
                'message' => 'Anda telah logout dari sistem'
            ]) : null;

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
