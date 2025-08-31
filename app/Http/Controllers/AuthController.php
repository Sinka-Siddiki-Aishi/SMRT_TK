<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class AuthController extends Controller
{
   public function showLoginForm()
   {
       return view('auth.login');
   }


   public function showRegistrationForm()
   {
       return view('auth.register');
   }


   public function showRegisterForm()
   {
       return view('auth.register');
   }


   public function login(Request $request)
   {
       $credentials = $request->only('email', 'password');
       if (Auth::attempt($credentials)) {
           $user = Auth::user();


           // Redirect based on user role
           if ($user->isOrganizer()) {
               return redirect()->route('organizer.dashboard');
           }

           return redirect()->route('user.dashboard');
       }
       return back()->withErrors([
           'email' => 'Invalid credentials.',
       ]);
   }


   public function register(Request $request)
   {
       $request->validate([
           'name' => 'required|string|max:255',
           'email' => 'required|string|email|max:255|unique:users',
           'password' => 'required|string|min:6|confirmed',
           'role' => 'required|in:user,organizer',
       ]);


       DB::transaction(function () use ($request, &$user) {
           $user = \App\Models\User::create([
               'name' => $request->name,
               'email' => $request->email,
               'password' => bcrypt($request->password),
               'role' => $request->role,
           ]);

           // Create a wallet for the user
           $user->wallet()->create(['balance' => 1000]); // Give a default balance
       });


       Auth::login($user);


       // Redirect based on role
       if ($user->isOrganizer()) {
           return redirect()->route('organizer.dashboard');
       }


       return redirect()->route('user.dashboard');
   }


   public function logout(Request $request)
   {
       Auth::logout();
       $request->session()->invalidate();
       $request->session()->regenerateToken();
       return redirect()->route('login')->with('success', 'You have been logged out successfully.');
   }


   public function profile()
   {
       return view('auth.profile');
   }


   public function updateProfile(Request $request)
   {
       $request->validate([
           'name' => 'required|string|max:255',
           'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
           'phone' => 'nullable|string|max:20',
           'address' => 'nullable|string|max:500',
       ]);


       $user = Auth::user();
       $user->update($request->only(['name', 'email', 'phone', 'address']));


       return back()->with('success', 'Profile updated successfully!');
   }


   public function updatePassword(Request $request)
   {
       $request->validate([
           'current_password' => 'required',
           'password' => 'required|string|min:8|confirmed',
       ]);


       $user = Auth::user();


       if (!Hash::check($request->current_password, $user->password)) {
           return back()->withErrors(['current_password' => 'Current password is incorrect.']);
       }


       $user->update([
           'password' => Hash::make($request->password),
       ]);


       return back()->with('success', 'Password updated successfully!');
   }
}