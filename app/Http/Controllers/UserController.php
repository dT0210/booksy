<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function create() {
        return view('users.signup');
    }

    public function sign_in() {
        return view('users.signin');
    }

    public function store(Request $request) {
        $formFields = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|confirmed|min:6'
        ]);

        $formFields['password'] = bcrypt($formFields['password']);
        $formFields['type'] = 'user';

        $user = User::create($formFields);

        auth()->login($user);
        return redirect('/')->with('message', 'Account created and signed in');
    }

    public function authenticate(Request $request) {
        $formFields = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (auth()->attempt($formFields, isset($request['remember']))) {
            $request->session()->regenerate();
            return redirect('/')->with('message', 'You are now signed in.');
        }

        return back()->withErrors(['email' => 'Invalid Credentials'])->onlyInput();
    }

    public function sign_out(Request $request) {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('message', 'You have been signed out.');
    }
    
}
