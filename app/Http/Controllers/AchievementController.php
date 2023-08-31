<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\User;
use App\Models\Profile;
use App\Models\Image;
use App\Models\Subject;

use App\Http\Requests\StoreAchievementRequest;
use App\Http\Requests\UpdateAchievementRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use File;

class AchievementController extends Controller
{
    public function __construct() {
        $this->metaTags = [
            'title' => 'Ruang Siswa',
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
        return view('achievement.index', [
            'dashboard_header' => '<i class="bx bxs-medal me-3"></i><span>Achievement</span>',
            'page_title' => "Ruang Siswa | Achievement",
            'achievements' => Achievement::orderByDesc('created_at')->get(),
            'subjects' => Subject::orderBy('name')->get(),
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
        return view('achievement.create', [
            'dashboard_header' => '<i class="bx bxs-medal me-3"></i><span>Achievement</span>',
            'page_title' => "Ruang Siswa | Achievement",
            'students' => $students,
            'subjects' => Subject::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAchievementRequest $request)
    {
        $user = User::find($request->user_id);

        // subject
        $subject_id = $request->subject_id;
        if($request->subject_id == '-') {
            if($request->subject == null) {
                return back()->with('error', 'Please input the subject name');
            }
            $subject = Subject::where('name', $request->subject)->first();
            if(!$subject) {
                $subject = Subject::create(['name' => $request->subject]);
            }
            $subject_id = $subject->id;
        }

        // processing image
        $files = [];
        if($request->images) {
            foreach($request->images as $key => $image) {
                $img_parts = explode(";base64,", $image);
                $img_type_aux = explode("image/", $img_parts[0]);
                $img_type = $img_type_aux[1];
                $img_base64 = base64_decode($img_parts[1]);
                $img_name = 'gallery-'.$user->username.'-'.time().'-'.($key+1).'.'.$img_type;
                $file = public_path('img/gallery/'.$img_name);
                file_put_contents($file, $img_base64);
                
                $files[$key]['name'] = $img_name;
                $files[$key]['caption'] = (isset($request->captions[$key])) ? $request->captions[$key] : '';
            }
        }
        dd($request->all());

        // certificate
        $certificate_image = '';
        if($request->file('certificate_image')) {
            $imageName = 'certificate-'.time().'.'.$request->file('certificate_image')->extension();  
            $storeImage = $request->file('certificate_image')->move(public_path('img/certificate'), $imageName);
            $certificate_image = $imageName;
        }
        $certificate_pdf = '';
        if($request->file('certificate_pdf')) {
            $PDFName = 'certificate-'.time().'.'.$request->file('certificate_pdf')->extension();  
            $storePDF = $request->file('certificate_pdf')->move(public_path('img/certificate/pdf'), $PDFName);
            $certificate_pdf = $PDFName;
        }

        // create achievement
        $create_achievement = Achievement::create([
            'user_id' => $request->user_id,
            'subject_id' => $subject_id,
            'title' => $request->title,
            'year' => $request->year,
            'organizer' => $request->organizer,
            'url' => $request->url,
            'description' => $request->description,
            'level' => $request->level,
            'grade_level' => $request->grade_level,
            'certificate_image' => $certificate_image,
            'certificate_pdf' => $certificate_pdf,
            'confirmed' => (Auth::user()->profile->role != 'student') ? true : false,
        ]);
        return back()->with('success', 'New achievement data stored');
    }

    /**
     * Display the specified resource.
     */
    public function show(Achievement $achievement)
    {
        return view('achievement.show', [
            'dashboard_header' => '<i class="bx bxs-medal me-3"></i><span>Achievement</span>',
            'page_title' => "Ruang Siswa | Achievement",
            'achievement' => $achievement,
            'user' => $achievement->user,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Achievement $achievement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAchievementRequest $request, Achievement $achievement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Achievement $achievement)
    {
        //
    }
    public function delete($achievement_id)
    {
        $achievement = Achievement::find($achievement_id);
        if($achievement->certificate_image != null) {
            $path = public_path('img/certificate/'.$achievement->certificate_image);
            if(File::exists($path)) {
                $delete_image = unlink($path);
            }
        } 
        if($achievement->certificate_pdf != null) {
            $path = public_path('img/certificate/pdf/'.$achievement->certificate_pdf);
            if(File::exists($path)) {
                $delete_pdf = unlink($path);
            }
        }
        $achievement->delete();
        return back()->with('success', 'Achievement data deleted');
    }
}
