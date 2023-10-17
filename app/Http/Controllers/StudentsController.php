<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use App\Models\Authority;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class StudentsController extends Controller
{
    public function __construct() {
        $this->metaTags = [
            'title' => 'Student Center',
            'description' => '',
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = User::whereHas('profile', function (Builder $query) {
            return $query->where('role', 'student')->orderBy('full_name');
        })->get();
        return view('students.index', [
            'metaTags' => $this->metaTags,
            'dashboard_header' => '<i class="bx bxs-group me-3"></i><span>Students</span>',
            'page_title' => "Student Center | Students",
            'students' => $students,
        ]);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function action(Request $request)
    {
        switch($request->action) {
            case 'get_students':
                $keywords = (isset($request->student_keywords)) ? $request->student_keywords : '';
                $students = User::whereHas('profile', function (Builder $query) use ($keywords) {
                    return $query->where('role', 'student')->where('full_name', 'like', '%'.$keywords.'%')->orderBy('full_name');
                })->with('profile')->get();
                return response()->json([
                    'students' => $students,
                ], 200);
            break;
        }
    }
}
