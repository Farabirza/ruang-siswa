<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function __construct() {
        $this->metaTags = [
            'title' => 'Ruang Siswa',
        ];
    }
    public function redirectToGoogle() {
        return Socialite::driver('google')->redirect();
    }
    public function handleGoogleCallback() {
        try {
            $google = Socialite::driver('google')->user();
            $user = User::where('google_id', $google->id)->first();
            if($user) {
                Auth::login($user);
                return redirect()->intended('/');
            } else {
                $user_by_email = User::where('email', $google->email)->first();
                if($user_by_email) {
                    Auth::login($user_by_email);
                    return redirect()->intended('/');
                }
                return view('user.create_username', [
                    'user' => $google,
                ]);
            }
        } catch (\Throwable $th) {
            return redirect('/?info=Authentication failed');
        }
    }
}
