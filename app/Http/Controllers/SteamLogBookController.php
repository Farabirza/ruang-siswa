<?php

namespace App\Http\Controllers;

use App\Models\SteamLogBook;
use App\Models\SteamProject;
use App\Models\SteamMember;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SteamLogBookController extends Controller
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
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($steam_id)
    {
        return view('steam.logbook.create', [
            'metaTags' => $this->metaTags,
            'dashboard_header' => '<i class="bx bx-analyse me-3"></i><span>STEAM Project</span>',
            'page_title' => "Student Center | STEAM",
            'steamProject' => SteamProject::find($steam_id),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SteamLogBook $steamLogBook)
    {
        $isMember = $this->isMember($steamLogBook->steamProject);
        return view('steam.logbook.show', [
            'metaTags' => $this->metaTags,
            'dashboard_header' => '<i class="bx bx-file me-3"></i><span>STEAM Log Book</span>',
            'page_title' => "Student Center | STEAM",
            'steamLogBook' => $steamLogBook,
            'isMember' => $isMember,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SteamLogBook $steamLogBook)
    {
        if($this->isMember($steamLogBook->steamProject->id) == false) {
            return redirect('/steamLogBook/'.$steamLogBook->id)->with('error', 'Unauthorized');
        }
        return view('steam.logbook.edit', [
            'metaTags' => $this->metaTags,
            'dashboard_header' => '<i class="bx bx-file me-3"></i><span>STEAM Log Book</span>',
            'page_title' => "Student Center | STEAM",
            'steamLogBook' => $steamLogBook,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SteamLogBook $steamLogBook)
    {
        if($request->content == null) {
            return response()->json([
                'message' => 'Content cannot be empty'
            ], 400);
        }

        // $content = $request->content;
        // $dom = new \DomDocument();
        // $dom->loadHtml($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        // $imageFile = $dom->getElementsByTagName('img');
  
        // foreach($imageFile as $item => $image){
        //     $data = $image->getAttribute('src');
        //     list($type, $data) = explode(';', $data);
        //     list(, $data)      = explode(',', $data);
        //     $imgeData = base64_decode($data);
        //     $image_name= "/img/steam/logbook/" . time().$item.'.png';
        //     $path = public_path() . $image_name;
        //     file_put_contents($path, $imgeData);
            
        //     $image->removeAttribute('src');
        //     $image->setAttribute('src', $image_name);
        // }
  
        // $content = $dom->saveHTML();

        $steamLogBook->update([
            'title' => ($request->title) ? $request->title : 'Log update for '.date('l, j F Y', time()),
            'content' => $request->content,
        ]);
        return response()->json([
            'message' => 'Log book updated',
            'steamLogBook_id' => $steamLogBook->id,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SteamLogBook $steamLogBook)
    {
        //
    }
    public function isMember($steam_id)
    {
        $steamProject = SteamProject::find($steam_id)->first();
        $isMember = false;
        if(Auth::check()) {
            foreach($steamProject->steamMember as $item) {
                $isMember = (Auth::user()->id == $item->user->id) ? true : $isMember;
            }
        }
        return $isMember;
    }
    public function action(Request $request)
    {
        switch($request->action) {
            case 'new_log':
                if($request->content == null) {
                    return response()->json([
                        'message' => 'Content cannot be empty'
                    ], 400);
                }
                $steamLogBook = SteamLogBook::create([
                    'user_id' => $request->user_id,
                    'steamProject_id' => $request->steamProject_id,
                    'title' => ($request->title) ? $request->title : 'Log update for '.date('l, j F Y', time()),
                    'content' => $request->content,
                ]);
                return response()->json([
                    'message' => 'A new log added to the project',
                    'steamLogBook_id' => $steamLogBook->id,
                ], 200);
            break;
        }
    }
}
