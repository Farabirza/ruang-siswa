<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use App\Models\Authority;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct() {
        $this->metaTags = [
            'title' => 'Student Center',
            'description' => 'Unlock the world of knowledge!',
        ];
    }
    public function user_controller()
    {
        return view('admin.admin_user', [
            'metaTags' => $this->metaTags,
            'dashboard_header' => '<i class="bx bxs-user me-3"></i><span>User Controller</span>',
            'page_title' => "Student Center | User Controller",
            'users' => User::orderBy('email')->get(),
            'authorities' => Authority::get(),
        ]);
    }
}
