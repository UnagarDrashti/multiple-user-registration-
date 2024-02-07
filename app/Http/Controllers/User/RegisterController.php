<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('customer.register');
    }
    
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:8',
                'confirm_password' => 'required|same:password'
            ]);
     
            if ($validator->fails()) {
                return redirect()
                            ->back()
                            ->withInput()
                            ->withErrors($validator);
            }
    
            $role = 'customer';
            User::create([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'role' => $role,
            ]);
            return redirect('/home')->with('success', 'Customer registered successfully.');
        } catch (\Exception $e) {
            echo $e;
            die;
        }
    }

    public function showLoginForm() {
        return view('customer.login');
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

    public function customerDashboard() {
        return view('customer.home');
    }

    public function logout(){  
        return redirect('customer/login')->with(auth()->logout());
    }
}
