@extends('layouts.master')

@push('css-styles')
<link href="{{ asset('/vendor/datatables/datatables.min.css') }}" rel="stylesheet">
<style>
table { font-size: .8em; }
.alert { font-size: .8em; padding: 10px; }
.dropdown-item { display: flex; align-items: center; gap: 8px; padding-left: 10px; }
.dropdown-item:hover { cursor: pointer; }
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
                    <li class="breadcrumb-item active" aria-current="page">Students</li>
                </ol>
            </nav>
        </div>
        <!-- breadcrumb end -->
    </div>

    <!-- container start -->
    <div class="container-fluid mb-3">
        <div class="row bg-white p-3 mx-2 rounded shadow">
            <div class="col-md-12">
                <h3 class="d-flex align-items-center gap-2 fs-16 mb-3"><i class="bx bx-group"></i>Student list</h3>
                <div class="table-container">
                    <table id="table-students" class="table table-striped">
                        <thead>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Grade</th>
                            <th><i class="bx bx-dots-vertical"></i></th>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @forelse($students as $item)
                            <?php 
                                $current_grade = $item->profile->grade + date('Y', time()) - $item->profile->year_join; 
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
                                <td>{{$i}}</td>
                                <td>{{ $item->profile->full_name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $grade_level }}</td>
                                <td>
                                    <div class="dropdown">
                                        <i class="bx bx-dots-vertical bx-border-circle btn-outline-dark p-1" role="button" data-bs-toggle="dropdown" aria-expanded="false"></i>
                                        <div class="dropdown-menu fs-9">
                                            <div class="dropdown-item" type="button" data-bs-toggle="offcanvas" data-bs-target="#studentProfile" aria-controls="studentProfile" onclick="offcanvasProfile('{{$i}}')"><i class="bx bx-file"></i>Profile</div>
                                            <div class="dropdown-item" onclick="modalPassword('{{$item->id}}', '{{$item->email}}')"><i class="bx bx-key"></i>Reset password</div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <p id="picture-{{$i}}" class="d-none">{{($item->picture ? asset('img/profiles/'.$item->picture) : asset('img/profiles/user.jpg'))}}</p>
                            <p id="email-{{$i}}" class="d-none">{{$item->email}}</p>
                            <p id="full_name-{{$i}}" class="d-none">{{$item->profile->full_name}}</p>
                            <p id="grade-{{$i}}" class="d-none">{{$grade_level}}</p>
                            <p id="address-{{$i}}" class="d-none">{{$item->profile->address}}</p>
                            <p id="birth_place-{{$i}}" class="d-none">{{$item->profile->birth_place}}</p>
                            <p id="birth_date-{{$i}}" class="d-none">{{($item->birth_date ? date('j F Y', strtotime($item->profile->birth_date)) : '')}}</p>
                            <?php $i++; ?>
                            @empty
                            <tr><td colspan="5" class="text-center fst-italic">Empty</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- container end -->

</section>

@include('layouts.partials.modal_students')

@endsection

@push('scripts')
<script src="{{ asset('/vendor/datatables/datatables.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
    new DataTable('#table-students', {
        pageLength: 100,
        fixedColumns: true,
        ordering: true,
        searching: true,
    });
    $('#link-students').addClass('active');
});
</script>
@endpush