<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use App\Models\Achievement;
use App\Models\SteamProject;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct() {
        $this->metaTags = [
            'title' => 'Student Center',
            'description' => 'Unlock the World of Knowledge!',
        ];
    }
    public function index()
    {   
        $alerts = [];
        if(Auth::check()) {
            $user = Auth::user();
            if(!$user->profile) {
                return redirect('/profile');
            }
            if($user->profile->role == 'student') {
                foreach($user->achievement as $item) {
                    if($item->confirmed == 0) {
                        $alerts['achievement'][] = 'You have an unconfirmed achievement record : <a href="/achievement/'.$item->id.'" class="hover-underline">'.$item->attainment.' '.$item->competition.'</a>';
                    }
                }
            } else {
                $users = User::get();
                foreach($users as $item) {
                    if($item->profile && $item->profile->role != 'student' && $item->confirmation == 0 && $item->status != 'suspended') {
                        $alerts['general'][] = 'a user with email : <a href="/profile/'.$item->profile->id.'" class="hover-underline">'.$item->email.'</a> is trying to sign in as a '.$item->profile->role.', is this data correct? <a href="/user/'.$item->id.'/confirm" class="hover-underline">Yes</a> | <a href="/user/'.$item->id.'/suspend" class="hover-underline btn-warn" data-warning="Suspend this user?">No</a>';
                    }
                }
                if(Achievement::where('confirmed', false)->first()) {
                    $alerts['general'][] = "<a href='/achievement'>A student achievement is waiting for your confirmation</a>";
                }
            }
        }
        return view('index', [
            'metaTags' => $this->metaTags,
            'page_title' => 'Student Center',
            'achievements' => Achievement::where('confirmed', true)->orderByDesc('created_at')->limit(12)->get(),
            'steamProjects' => SteamProject::orderByDesc('created_at')->limit(12)->get(),
            'alerts' => $alerts,
        ]);
    }
    public function action(Request $request)
    {
        switch ($request->action) {
        }
    }
}
