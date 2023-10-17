<?php

namespace App\Http\Controllers;

use App\Models\SteamCategory;
use App\Models\SteamProject;
use App\Models\SteamMember;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use File;

class SteamCategoryController extends Controller
{
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // image handler
        $imageName = '';
        if($request->file('image')) {
            $imageName = 'category-'.time().'.'.$request->file('image')->extension();  
            $storeImage = $request->file('image')->move(public_path('img/steam/categories'), $imageName);
        }

        $create_category = SteamCategory::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imageName,
        ]);
        return back()->with('success', "New project category created");
    }

    /**
     * Display the specified resource.
     */
    public function show(SteamCategory $steamCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SteamCategory $steamCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SteamCategory $steamCategory)
    {
        // image handler
        $oldImage = ($steamCategory->image) ? $steamCategory->image : '';
        $imageName = $oldImage;
        if($request->file('image')) {
            $imageName = 'category-'.time().'.'.$request->file('image')->extension();  
            $storeImage = $request->file('image')->move(public_path('img/steam/categories/'), $imageName);
            if($oldImage) {
                $path = public_path('img/steam/categories/'.$oldImage);
                if(File::exists($path)) {
                    $delete_image = unlink($path);
                }
            }
        }

        $steamCategory->update([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imageName,
        ]);
        return back()->with('success', "Project category updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SteamCategory $steamCategory)
    {
        //
    }
}
