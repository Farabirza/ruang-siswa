<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\User;
use App\Models\Profile;
use App\Models\Image;
use App\Models\Comment;
use App\Models\Subject;

use App\Http\Requests\StoreAchievementRequest;
use App\Http\Requests\UpdateAchievementRequest;
use App\Http\Requests\StoreCommentRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use File;

class AchievementController extends Controller
{
    public function __construct() {
        $this->metaTags = [
            'title' => 'Student Center',
            'description' => '',
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
            'metaTags' => $this->metaTags,
            'dashboard_header' => '<i class="bx bxs-medal me-3"></i><span>Achievement</span>',
            'page_title' => "Student Center | Achievement",
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
            'metaTags' => $this->metaTags,
            'dashboard_header' => '<i class="bx bxs-medal me-3"></i><span>Achievement</span>',
            'page_title' => "Student Center | New Achievement",
            'students' => $students,
            'subjects' => Subject::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAchievementRequest $request)
    {
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
            'attainment' => $request->attainment,
            'competition' => $request->competition,
            'start_date' => $request->start_date,
            'end_date' => ($request->start_date <= $request->end_date) ? $request->end_date : $request->start_date,
            'organizer' => $request->organizer,
            'url' => $request->url,
            'description' => $request->description,
            'level' => $request->level,
            'grade_level' => $request->grade_level,
            'certificate_image' => $certificate_image,
            'certificate_pdf' => $certificate_pdf,
            'confirmed' => (Auth::user()->profile->role != 'student') ? true : false,
        ]);
        return redirect('/achievement/'.$create_achievement->id.'/edit')->with('success', 'New achievement data stored');
    }

    /**
     * Display the specified resource.
     */
    public function show(Achievement $achievement)
    {
        if($achievement->confirmed == 0) {
            if(!Auth::check() || (Auth::user()->profile->role == 'student' && $achievement->user_id != Auth::user()->id)) {
                return redirect('/')->with('info', "This page is inaccessible");
            }
        }
        return view('achievement.show', [
            'metaTags' => $this->metaTags,
            'dashboard_header' => '<i class="bx bxs-medal me-3"></i><span>Achievement</span>',
            'page_title' => "Student Center | Achievement",
            'achievement' => $achievement,
            'user' => User::find($achievement->user_id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Achievement $achievement)
    {
        if(Auth::user()->profile->role == 'student' && Auth::user()->id != $achievement->user_id) {
            return redirect('/')->with('error', 'Access denied');
        }
        return view('achievement.edit', [
            'metaTags' => $this->metaTags,
            'dashboard_header' => '<i class="bx bxs-medal me-3"></i><span>Achievement</span>',
            'page_title' => "Student Center | Edit Achievement",
            'achievement' => $achievement,
            'subjects' => Subject::orderBy('name')->get(),
            'user' => $achievement->user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAchievementRequest $request, Achievement $achievement)
    {
        $this->validateError();
        $refresh = false;

        // subject
        $subject_id = $request->subject_id;
        if($request->subject_id == '-') {
            if($request->subject == null) {
                return response()->json([
                    'message' => "Please input the subject name",
                ], 400);
            }
            $subject = Subject::where('name', $request->subject)->first();
            if(!$subject) {
                $subject = Subject::create(['name' => $request->subject]);
                $refresh = true;
            }
            $subject_id = $subject->id;
        }

        // update achievement
        $achievement->update([
            'subject_id' => $subject_id,
            'attainment' => $request->attainment,
            'competition' => $request->competition,
            'start_date' => $request->start_date,
            'end_date' => ($request->start_date <= $request->end_date) ? $request->end_date : $request->start_date,
            'organizer' => $request->organizer,
            'url' => $request->url,
            'description' => $request->description,
            'level' => $request->level,
            'grade_level' => $request->grade_level,
            'confirmed' => (Auth::user()->profile->role != 'student') ? true : $achievement->confirmed,
        ]);
        return response()->json([
            'message' => 'Achievement data updated',
            'refresh' => $refresh,
        ], 200);
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

        // delete certificate image
        if($achievement->certificate_image != null) {
            $path = public_path('img/certificate/'.$achievement->certificate_image);
            if(File::exists($path)) {
                $delete_image = unlink($path);
            }
        } 
        
        // delete certificate pdf
        if($achievement->certificate_pdf != null) {
            $path = public_path('img/certificate/pdf/'.$achievement->certificate_pdf);
            if(File::exists($path)) {
                $delete_pdf = unlink($path);
            }
        }
        
        // delete achievement image
        if(count($achievement->image) > 0) {
            foreach($achievement->image as $item) {
                $path = public_path('img/photos/'.$item->name);
                if(File::exists($path)) {
                    $delete_pdf = unlink($path);
                }
                $item->delete();
            }
        }

        $achievement->delete();
        return redirect('/achievement')->with('success', 'Achievement data deleted');
    }
    public function confirm($achievement_id)
    {
        $achievement = Achievement::find($achievement_id)->update(['confirmed' => 1]);
        return back()->with('success', "Achievement data confirmed");
    }
    public function remove_image($achievement_id)
    {
        $achievement = Achievement::find($achievement_id);
        // remove old pdf
        if($achievement->certificate_image) {
            $path = public_path('img/certificate/'.$achievement->certificate_image);
            if(File::exists($path)) {
                $delete_image = unlink($path);
            }
        } else {
            return back()->with('error', "Certificate image data already empty");
        }
        // update achievement
        $achievement->update(['certificate_image' => '']);
        return back()->with('success', 'Certificate image removed ');
    }
    public function remove_pdf($achievement_id)
    {
        $achievement = Achievement::find($achievement_id);
        // remove old pdf
        if($achievement->certificate_pdf) {
            $path = public_path('img/certificate/pdf/'.$achievement->certificate_pdf);
            if(File::exists($path)) {
                $delete_image = unlink($path);
            }
        } else {
            return back()->with('error', "Certificate PDF data already empty");
        }
        // update achievement
        $achievement->update(['certificate_pdf' => '']);
        return back()->with('success', 'Certificate PDF removed ');
    }
    public function store_comment(StoreCommentRequest $request, $achievement_id)
    {
        $achievement = Achievement::find($achievement_id);
        $achievement_comment = $achievement->comment()->create([ 
            'content' => $request->content, 
            'user_id' => $request->user_id, 
        ]);
        return back()->with('success', "Comment posted");
    }
    public function action(Request $request)
    {
        switch($request->action) {
            case 'updateAchievementImage':
                if(!$request->caption) {
                    return response()->json([
                        'message' => "Caption empty",
                    ], 400);
                }
                $image = Image::find($request->image_id)->update(['caption' => $request->caption]);
                return response()->json([
                    "message" => "Achievement image data updated",
                    "refresh" => true,
                ], 200);
            break;
            case 'uploadAchievementImage':
                $achievement = Achievement::find($request->achievement_id);
                $user = User::find($request->user_id);

                // Create image
                $image = $request->achievement_image;
                $img_parts = explode(";base64,", $image);
                $img_type_aux = explode("image/", $img_parts[0]);
                $img_type = $img_type_aux[1];
                $img_base64 = base64_decode($img_parts[1]);
                $img_name = 'achievement-'.$user->username.'-'.time().'.'.$img_type;
                $file = public_path('img/photos/'.$img_name);
                file_put_contents($file, $img_base64);
                $achievement_image = $achievement->image()->create([ 'name' => $img_name ]);

                return response()->json([
                    'message' => "New achievement image added",
                    'image_id' => $achievement_image->id,
                ], 200);
            break;
            case 'uploadCertificatePdf':
                $achievement = Achievement::find($request->achievement_id);
                $user = User::find($achievement->user_id);
                $request->validate([
                    'certificate_pdf' => 'required|mimes:pdf|max:2048',
                ]);
                
                // processing pdf
                $PDFName = 'certificate-'.$user->username.'-'.time().'.'.$request->file('certificate_pdf')->extension();  
                $storePDF = $request->file('certificate_pdf')->move(public_path('img/certificate/pdf'), $PDFName);

                // remove old pdf
                if($achievement->certificate_pdf) {
                    $path = public_path('img/certificate/pdf/'.$achievement->certificate_pdf);
                    if(File::exists($path)) {
                        $delete_image = unlink($path);
                    }
                }

                // update achievement
                $achievement->update(['certificate_pdf' => $PDFName]);

                return back()->with('success', "Certificate pdf uploaded");
            break;
            case 'uploadCertificateImage':
                $achievement = Achievement::find($request->achievement_id);
                $user = User::find($achievement->user_id);
                if(!$request->certificate_image_base64) {
                    return response()->json([
                        'message' => "Image empty",
                    ], 400);
                }

                $image = $request->certificate_image_base64;
                $img_parts = explode(";base64,", $image);
                $img_type_aux = explode("image/", $img_parts[0]);
                $img_type = $img_type_aux[1];
                $img_base64 = base64_decode($img_parts[1]);
                $img_name = 'certificate-'.$user->username.'-'.time().'.'.$img_type;
                $file = public_path('img/certificate/'.$img_name);
                file_put_contents($file, $img_base64);

                if($achievement->certificate_image) {
                    $path = public_path('img/certificate/'.$achievement->certificate_image);
                    if(File::exists($path)) {
                        $delete_image = unlink($path);
                    }
                }
                $achievement->update([
                    'certificate_image' => $img_name,
                ]);
                return response()->json([
                    'message' => "Certificate image uploaded",
                ], 200);
            break;
        }
    }
}
