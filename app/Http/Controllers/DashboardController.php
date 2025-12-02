<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;   
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function redirectBasedOnRole()
    {
        $role = Auth::user()->role;

        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        } 
        
        return redirect()->route('user.dashboard');
    }

    public function adminDashboard()
    {
        return view('dashboard.admin', ['user' => Auth::user()]);
    }

    public function userDashboard()
    {
        // Logika untuk mengambil data khusus peminjam (riwayat pengajuan)
        return view('dashboard.user', ['user' => Auth::user()]);
    }


}