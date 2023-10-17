<?php

namespace App\Http\Controllers;

use App\Models\SteamProject;
use App\Models\SteamMember;
use App\Models\SteamCategory;
use App\Models\SteamLogBook;
use App\Models\User;
use App\Models\Image;
use App\Models\Comment;

use App\Http\Requests\StoreSteamProjectRequest;
use App\Http\Requests\UpdateSteamProjectRequest;
use App\Http\Requests\StoreCommentRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use File;

class SteamProjectController extends Controller
{
    public function __construct() {
        $this->metaTags = [
            'title' => 'Student Center',
            'description' => 'Unlock the world of knowledge!',
        ];
    }
    public function validateError()
    {
        if(isset($validator) && $validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('steam.index', [
            'metaTags' => $this->metaTags,
            'dashboard_header' => '<i class="bx bx-analyse me-3"></i><span>STEAM Project</span>',
            'page_title' => "Student Center | STEAM",
            'steamLogBooks' => SteamLogBook::orderByDesc('created_at')->get(),
            'steamProjects' => SteamProject::orderByDesc('created_at')->paginate(12),
            'steamCategories' => SteamCategory::orderBy('name')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = User::whereHas('profile', function (Builder $query) {
            return $query->where('role', 'student')->orderBy('full_name');
        })->get();
        return view('steam.create', [
            'metaTags' => $this->metaTags,
            'dashboard_header' => '<i class="bx bx-analyse me-3"></i><span>New project</span>',
            'page_title' => "Student Center | STEAM",
            'students' => $students,
            'steamCategories' => SteamCategory::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSteamProjectRequest $request)
    {
        $this->validateError();
        $create_steam = SteamProject::create([
            'title' => $request->title,
            'description' => $request->description,
            'steamCategory_id' => $request->steamCategory_id,
        ]);

        // upload cover image
        if($request->image_base64 != null) {
            $image = $request->image_base64;
            $img_parts = explode(";base64,", $image);
            $img_type_aux = explode("image/", $img_parts[0]);
            $img_type = $img_type_aux[1];
            $img_base64 = base64_decode($img_parts[1]);
            $img_name = 'STEAM-'.Auth::user()->username.'-'.time().'.'.$img_type;
            $file = public_path('img/steam/'.$img_name);
            file_put_contents($file, $img_base64);
            $create_steam->update([ 'image' => $img_name ]);
        }

        // if student, be a member
        if(Auth::user()->profile->role == "student") {
            SteamMember::create([
                'user_id' => Auth::user()->id,
                'steamProject_id' => $create_steam->id,
            ]);
        }

        return response()->json([
            'message' => 'New STEAM project created',
            'steam_id' => $create_steam->id,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(SteamProject $steamProject)
    {
        $isMember = false;
        if(Auth::check()) {
            foreach($steamProject->steamMember as $item) {
                $isMember = (Auth::user()->id == $item->user->id) ? true : $isMember;
            }
        }
        return view('steam.show', [
            'metaTags' => $this->metaTags,
            'dashboard_header' => '<i class="bx bx-analyse me-3"></i><span>STEAM Project</span>',
            'page_title' => "Student Center | STEAM",
            'steam' => $steamProject,  
            'isMember' => $isMember,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SteamProject $steamProject)
    {
        return view('steam.edit', [
            'metaTags' => $this->metaTags,
            'dashboard_header' => '<i class="bx bx-analyse me-3"></i><span>STEAM Project</span>',
            'page_title' => "Student Center | STEAM",
            'steam' => $steamProject,
            'steamCategories' => SteamCategory::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSteamProjectRequest $request, SteamProject $steamProject)
    {
        $this->validateError();
        $steamProject->update([
            'title' => $request->title,
            'description' => $request->description,
            'steamCategory_id' => $request->steamCategory_id,
        ]);

        $old_image = ($steamProject->image) ? $steamProject->image : null;
        
        // upload cover image
        if($request->image_base64 != null) {
            $image = $request->image_base64;
            $img_parts = explode(";base64,", $image);
            $img_type_aux = explode("image/", $img_parts[0]);
            $img_type = $img_type_aux[1];
            $img_base64 = base64_decode($img_parts[1]);
            $img_name = 'STEAM-'.Auth::user()->username.'-'.time().'.'.$img_type;
            $file = public_path('img/steam/'.$img_name);
            file_put_contents($file, $img_base64);
            $steamProject->update([ 'image' => $img_name ]);
            if($old_image) {
                $path = public_path('img/steam/'.$old_image);
                if(File::exists($path)) {
                    $delete_image = unlink($path);
                }
            }
        }

        return response()->json([
            'message' => 'Project detail updated',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($steam_id)
    {
        $steamProject = SteamProject::find($steam_id);
        if($this->isMember($steam_id) == false) {
            return redirect('/steamProject/'.$steamProject->id)->with('error', 'Unauthorized');
        }
        if($steamProject->image) {
            $path = public_path('img/steam/'.$steamProject->image);
            $delete_image = (File::exists($path)) ? unlink($path) : false;
        }
        foreach($steamProject->steamMember as $item) {
            $item->delete();
        }
        foreach($steamProject->steamLogBook as $item) {
            $item->delete();
        }
        $steamProject->delete();
        return redirect('/steamProject')->with('info', 'Project '.$steamProject->title.' deleted');
    }
    public function store_comment(StoreCommentRequest $request, $steam_id)
    {
        $steamProject = SteamProject::find($steam_id);
        $steamProject_comment = $steamProject->comment()->create([ 
            'content' => $request->content, 
            'user_id' => $request->user_id, 
        ]);
        return back()->with('success', "Comment posted");
    }
    public function isMember($steam_id)
    {
        $steamProject = SteamProject::find($steam_id);
        $isMember = false;
        if(Auth::check()) {
            if(Auth::user()->profile->role != 'student') {
                $isMember = true;
            } else {
                foreach($steamProject->steamMember as $item) {
                    $isMember = (Auth::user()->id == $item->user->id) ? true : $isMember;
                }
            }
        }
        return $isMember;
    }
    public function action(Request $request) 
    {
        switch($request->action) {
            case 'new_project':
                return response()->json([
                    'message' => 'connected',
                ], 200);
            break;
            case 'fetch_members':
                $steamMember = SteamMember::where('steamProject_id', $request->steam_id)->get();
                $students = [];
                foreach($steamMember as $item) {
                    $user = User::where('id', $item->user->id)->with('profile')->first();
                    $students[] = $user;
                }
                return response()->json([
                    'students' => $students,
                ], 200);
            break;
            case 'add_member':
                if($this->isMember($request->steam_id) == false) {
                    return response()->json([
                        'message' => "Unauthorized",
                    ], 401);
                }
                $user = User::find($request->user_id);
                $steamProject = SteamProject::find($request->steam_id);
                $message = '';
                $error = false;
                if(!$user) {
                    $error = true;
                    $message = 'User not found';
                } if(!$steamProject) {
                    $error = true;
                    $message = 'Project not found';
                } else {
                    foreach($steamProject->steamMember as $item) {
                        if($item->user_id == $user->id) {
                            $error = true;
                            $message = $user->profile->full_name.' is already a member of the project';
                        }
                    }
                }
                if($error == true) {
                    return response()->json([
                        'message' => $message,
                    ], 400);
                }
                SteamMember::create([
                    'user_id' => $user->id,
                    'steamProject_id' => $steamProject->id,
                ]);
                return response()->json([
                    'message' => 'New member added',
                ], 200);
            break;
            case 'remove_member':
                if($this->isMember($request->steam_id) == false) {
                    return response()->json([
                        'message' => "Unauthorized",
                    ], 401);
                }
                $steamProject = SteamProject::find($request->steam_id);
                $steamMember = SteamMember::where('user_id', $request->user_id)->where('steamProject_id', $request->steam_id)->first();
                if(!$steamMember) {
                    return response()->json([
                        'message' => "Not found",
                    ], 400);
                }
                if(count($steamProject->steamMember) <= 1) {
                    return response()->json([
                        'message' => "Please assign at least one member",
                    ], 400);
                } else {
                    $message = $steamMember->user->profile->full_name.' is removed from the project';
                    $steamMember->delete();
                }
                return response()->json([
                    'message' => ($message) ? $message : '',
                ], 200);
            break;
        }
    }
}
