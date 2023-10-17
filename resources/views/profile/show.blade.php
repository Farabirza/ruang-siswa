@extends('layouts.master')

@push('css-styles')
<link href="{{ asset('/vendor/datatables/datatables.min.css') }}" rel="stylesheet">
<style>
table { font-size: .8em; }
</style>
@endpush

@section('content')

<section>
    <div class="container pt-3 my-3">
        <!-- breadcrumb start -->
        <div class="col-md-12">
            <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    @if($user->profile->role == 'student')
                    <li class="breadcrumb-item"><a href="/students">Students</a></li>
                    @else
                    <li class="breadcrumb-item">User</li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">{{$profile->full_name}}</li>
                </ol>
            </nav>
        </div>
        <!-- breadcrumb end -->
    </div>

    <!-- container start -->
    <div class="container mb-4">
        <div class="row bg-white p-3 rounded shadow">
            <div class="col-md-12 py-2">
                <h3 class="d-flex align-items-center justify-content-between gap-2 fs-16 mb-4"><span class="flex-start gap-2"><i class="bx bx-user"></i>Profile</span></h3>
                <div class="d-flex align-items-center flex-remove-md gap-3 mb-3">
                    <div class="mb-2 text-center">
                        @if($user->picture)
                        <img src="{{ asset('img/profiles/'.$user->picture) }}" alt="" class="rounded shadow" style="max-height:240px">
                        @else
                        <img src="{{ asset('img/profiles/user.jpg') }}" alt="" class="rounded shadow" style="max-height:240px">
                        @endif
                        <h5 class="text-primary fs-10 ls-1 mt-3 mb-1">{{$user->profile->full_name}}</h5>
                    </div>
                    <div class="col mb-2">
                        <table class="table">
                            <tr>
                                <td class="text-primary fw-500">Role</td>
                                <td class="text-primary px-2">:</td>
                                <td>{{ucfirst($profile->role)}}</td>
                            </tr>
                            @if($profile->role == "student")
                            <?php 
                                $current_grade = $profile->grade + date('Y', time()) - $profile->year_join; 
                                if($current_grade <= 6) {
                                    $grade_level = 'Grade '.$current_grade.' Elementary';
                                } elseif($current_grade >= 7 && $current_grade <= 9) {
                                    $grade_level = 'Grade '.$current_grade.' Junior high';
                                } elseif($current_grade >= 10 && $current_grade <= 12) {
                                    $grade_level = 'Grade '.$current_grade.' Senior high';
                                } else {
                                    $grade_level = 'Alumni';
                                }
                            ?>
                            <tr>
                                <td class="text-primary fw-500">Grade</td>
                                <td class="text-primary px-2">:</td>
                                <td>{{$grade_level}}</td>
                            </tr>
                            @endif
                            <tr>
                                <td class="text-primary fw-500">Gender</td>
                                <td class="text-primary px-2">:</td>
                                <td>{{ucfirst($profile->gender)}}</td>
                            </tr>
                            <tr>
                                <td class="text-primary fw-500">Birth date</td>
                                <td class="text-primary px-2">:</td>
                                <td>{{($profile->birth_date ? date("j F Y", strtotime($profile->birth_date)) : '-')}}</td>
                            </tr>
                            <tr>
                                <td class="text-primary fw-500">Address</td>
                                <td class="text-primary px-2">:</td>
                                <td>{{($profile->address ? $profile->address : '-')}}</td>
                            </tr>
                            <tr>
                                <td class="text-primary fw-500">Biodata</td>
                                <td class="text-primary px-2">:</td>
                                <td>{{($profile->biodata ? $profile->biodata : '-')}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- container end -->
    
    <!-- container start -->
    <div class="container mb-4">
        <div class="row bg-white p-3 rounded shadow">
            <div class="col-md-12 py-2">
                <h3 class="d-flex align-items-center justify-content-between gap-2 fs-16 mb-4"><span class="flex-start gap-2"><i class="bx bx-medal"></i>Achievement</span></h3>
                <table class="table table-striped" id="table-achievements">
                    <thead>
                        <th>Date</th>
                        <th>Title</th>
                        <th>Grade</th>
                        <th>Level</th>
                        <th>Organizer</th>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @forelse($user->achievement->sortByDesc('created_at') as $item)
                        @if($item->confirmed == 1)
                        <tr>
                            <td>{{($item->start_date ? date('j F Y', strtotime($item->start_date)) : '-')}}</td>
                            <td><a href="/achievement/{{$item->id}}" class="hover-primary">{{$item->attainment.' '.$item->competition}}</a></td>
                            <td>{{$item->grade_level}}</td>
                            <td>{{$item->level}}</td>
                            <td>{{($item->organizer) ? $item->organizer : '-'}}</td>
                        </tr>
                        <?php $i++; ?>
                        @endif
                        @empty
                        <tr><td colspan="5" class="text-center fst-italic">empty</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- container end -->

</section>

@endsection

@push('scripts')
<script src="{{ asset('/vendor/datatables/datatables.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
    new DataTable('#table-achievements', {
        pageLength: 100,
        fixedColumns: true,
        ordering: true,
        searching: true,
    });
});
</script>
@endpush