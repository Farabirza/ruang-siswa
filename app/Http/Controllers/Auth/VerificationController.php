<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Foundation\Auth\VerifiesEmails;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Http\Controllers\Auth\VerificationController;

class VerificationController extends Controller
{
    public function __construct() {
        $this->metaTags = [
            'title' => 'Ruang Siswa',
            'description' => 'Unlock the world of knowledge!',
        ];
    }
    public function verify(EmailVerificationRequest $request)
    {    
        $request->fulfill();
        return redirect('/home');
    }
    public function resend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('resent', 'Verification link sent!');
    }
}
