<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GeneralController extends Controller
{
    public function __construct() {
        $this->metaTags = [
            'title' => 'Ruang Siswa',
            'description' => 'Unlock the World of Knowledge!',
        ];
    }
    public function index()
    {   
        return view('index', [
            'metaTags' => $this->metaTags,
            'page_title' => 'Ruang Siswa',
        ]);
    }
    public function action(Request $request)
    {
        switch ($request->action) {
        }
    }
}
