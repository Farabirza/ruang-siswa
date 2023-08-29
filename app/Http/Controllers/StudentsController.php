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
            'title' => 'Ruang Siswa',
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
            'dashboard_header' => '<i class="bx bxs-group me-3"></i><span>Students</span>',
            'page_title' => "Ruang Siswa | Students",
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
}
