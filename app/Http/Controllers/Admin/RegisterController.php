<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('admin.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
        ]);
        // Create a new customer
        $role = 'admin';
        User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'role' => $role,
        ]);
        // dd($user);
        return redirect('/')->with('success', 'Customer registered successfully.');
    }

    public function showLoginForm() {
        return view('admin.login');
    }

    public function login(Request $request) {
        try {
            if (auth()->attempt($request->only('email', 'password'), $request->remember)) {
                return redirect('home')->with('success', 'Login Successfully!' );
            }
        } catch (\Exception $e) {
            echo $e;die;
        }
    }

    public function adminDashboard() {
        return view('admin.home');
    }

    public function logout(){  
        return redirect('admin/login')->with(auth()->logout());
    }
}
