@extends('layouts.master')

@push('css-styles')
<link href="{{ asset('/vendor/datatables/datatables.min.css') }}" rel="stylesheet">
<style>
table { font-size: .8em; }
.alert { font-size: .8em; padding: 10px; }
.dropdown-item { display: flex; align-items: center; gap: 8px; padding-left: 10px; }
.dropdown-item:hover { cursor: pointer; }
@media (max-width:1199px) {
    .table-container { min-width: 768px; }
}
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
                    <li class="breadcrumb-item active" aria-current="page"><a href="/achievement">Achievement</a></li>
                </ol>
            </nav>
        </div>
        <!-- breadcrumb end -->
    </div>

    <!-- container start -->
    <div class="container-fluid mb-4">
        <div class="row bg-white p-3 mx-2 rounded shadow">
            <div class="col-md-12 py-2">
                <h3 class="d-flex align-items-center justify-content-between gap-2 fs-16 mb-3"><span class="flex-start gap-2"><i class="bx bx-list-ul"></i>Achievement list</span><a href="/achievement/create" class="popper" title="New achievement"><i class="bx bx-plus p-1 rounded-circle border border-primary btn-outline-primary"></i></a></h3>
                <div class="table-container">
                    <table class="table table-striped" id="table-achievements">
                        <thead>
                            <th>Name</th>
                            <th>Attainment</th>
                            <th>Competition</th>
                            <th>Grade</th>
                            <th>Level</th>
                            <th>Subject</th>
                            <th>Organizer</th>
                            @auth
                            <th><i class="bx bx-dots-vertical"></i></th>
                            @endauth
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @forelse($achievements as $item)
                            @if($item->confirmed == 1)
                            <tr>
                                <td><a href="/profile/{{$item->user->profile->id}}" class="hover-primary">{{ $item->user->profile->full_name }}</a></td>
                                <td><a href="/achievement/{{$item->id}}" class="hover-primary">{{$item->attainment}}</a></td>
                                <td><a href="/achievement/{{$item->id}}" class="hover-primary">{{$item->competition}}</a></td>
                                <td>{{$item->grade_level}}</td>
                                <td>{{$item->level}}</td>
                                <td>{{$item->subject->name}}</td>
                                <td>{{($item->organizer) ? $item->organizer : '-'}}</td>
                                @auth
                                <td>
                                    <div class="dropdown">
                                        <i class="bx bx-dots-vertical bx-border-circle btn-outline-dark p-1" role="button" data-bs-toggle="dropdown" aria-expanded="false"></i>
                                        <div class="dropdown-menu fs-9">
                                            <a href="/achievement/{{$item->id}}"><div class="dropdown-item"><i class="bx bx-file"></i>Detail</div></a>
                                            <a href="/achievement/{{$item->id}}/edit"><div class="dropdown-item"><i class="bx bx-edit-alt"></i>Edit</div></a>
                                            <a href="/achievement/{{$item->id}}/delete" class="btn-warn" data-warning="Do you wish to delete this achievement?"><div class="dropdown-item"><i class="bx bx-trash-alt"></i>Delete</div></a>
                                        </div>
                                    </div>
                                </td>
                                @endauth
                            </tr>
                            <?php $i++; ?>
                            @endif
                            @empty
                            <tr><td colspan="100%" class="text-center fst-italic">empty</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- container end -->
    
    @if(Auth::check() && Auth::user()->profile->role != 'student')
    <!-- container start -->
    <div class="container-fluid mb-4">
        <div class="row bg-white p-3 mx-2 rounded shadow">
            <div class="col-md-12 py-2">
                <h3 class="d-flex align-items-center justify-content-between gap-2 fs-16 mb-3"><span class="text-danger flex-start gap-2"><i class="bx bx-check-square"></i>Unconfirmed</span></h3>
                <p class="fs-9">These achievements cannot be seen by regular users until they are confirmed by any teacher or admin</p>
                <div class="table-container">
                    <table class="table table-striped" id="table-unconfirmed">
                        <thead>
                            <th>Name</th>
                            <th>Title</th>
                            <th><i class="bx bx-dots-vertical"></i></th>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @forelse($achievements as $item)
                            @if($item->confirmed == 0)
                            <tr>
                                <td>{{($item->user->profile ? $item->user->profile->full_name : $item->user->email)}}</td>
                                <td><a href="/achievement/{{$item->id}}" class="hover-primary">{{$item->attainment.' '.$item->competition}}</a></td>
                                <td>
                                    <div class="dropdown">
                                        <i class="bx bx-dots-vertical bx-border-circle btn-outline-dark p-1" role="button" data-bs-toggle="dropdown" aria-expanded="false"></i>
                                        <div class="dropdown-menu fs-9">
                                            <a href="/achievement/{{$item->id}}/confirm"><div class="dropdown-item"><i class="bx bx-check-double"></i>Confirm</div></a>
                                            <a href="/achievement/{{$item->id}}"><div class="dropdown-item"><i class="bx bx-file"></i>Detail</div></a>
                                            <a href="/achievement/{{$item->id}}/edit"><div class="dropdown-item"><i class="bx bx-edit-alt"></i>Edit</div></a>
                                            <a href="/achievement/{{$item->id}}/delete" class="btn-warn" data-warning="Do you wish to delete this achievement?"><div class="dropdown-item"><i class="bx bx-trash-alt"></i>Delete</div></a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php $i++; ?>
                            @endif
                            @empty
                            <tr><td colspan="3" class="text-center fst-italic">empty</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- container end -->
    @endif

</section>

@include('layouts.partials.modal_students')

@endsection

@push('scripts')
<script src="{{ asset('/vendor/datatables/datatables.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
    if($('#table-achievements tbody tr').length == 0) {
        $('#table-achievements tbody').append(`
            <tr><td colspan="100%" class="text-center">no achievement data has been confirmed yet</td></tr>
        `);
    }
    if($('#table-unconfirmed tbody tr').length == 0) {
        $('#table-unconfirmed tbody').append(`
            <tr><td colspan="100%" class="text-center">currently there is no unconfirmed achievement data</td></tr>
        `);
    }
    new DataTable('#table-achievements', {
        pageLength: 100,
        fixedColumns: true,
        ordering: true,
        searching: true,
    });
    $('#link-achievement').addClass('active');
    $('#submenu-achievement').addClass('show');
    $('#link-achievement-index').addClass('active');
});
</script>
@endpush