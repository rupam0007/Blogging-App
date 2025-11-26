<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    
    public function showRegistrationForm()
    {
        return view('registration'); 
    }

    
    public function register(Request $request)
    {

        $request->validate([

            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'dob' => ['required', 'date'],
            'phone' => ['required', 'string', 'max:20'],
            

            'village' => ['nullable', 'string', 'max:255'],
            'post' => ['nullable', 'string', 'max:255'],
            'police_station' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],


            'password' => [
                'required', 
                'confirmed', 
                'min:6'
            ],
        ]);
        

        // Generate unique username from email
        $baseUsername = strtolower(explode('@', $request->email)[0]);
        $username = $baseUsername;
        $counter = 1;
        
        // Ensure username is unique
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }
        
        $user = User::create([
            'name' => $request->name,
            'username' => $username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'dob' => $request->dob,
            'phone' => $request->phone,
            'village' => $request->village,
            'post' => $request->post,
            'police_station' => $request->police_station,
            'district' => $request->district,
            'role' => 'user', // Set default role
        ]);


        Auth::login($user);


        return redirect()->route('home')->with('success', 'Registration successful! Welcome aboard.');
    }
}