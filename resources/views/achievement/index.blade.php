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
                    <li class="breadcrumb-item"><a href="/achievement">Achievement</a></li>
                </ol>
            </nav>
        </div>
        <!-- breadcrumb end -->
    </div>

    <!-- container start -->
    <div class="container-fluid mb-3">
        <div class="row bg-white p-3 rounded shadow">
            <div class="col-md-12 py-2">
                <h3 class="d-flex align-items-center justify-content-between gap-2 fs-16 mb-3"><span><i class="bx bx-list-ul"></i>Achievement list</span><a href="/achievement/create" class="popper" title="New achievement"><i class="bx bx-plus p-1 rounded-circle border border-primary btn-outline-primary"></i></a></h3>
                <div class="table-container">
                    <table class="table table-striped" id="table-achievements">
                        <thead>
                            <th>Name</th>
                            <th>Title</th>
                            <th>Grade</th>
                            <th>Level</th>
                            <th><i class="bx bx-dots-vertical"></i></th>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @forelse($achievements as $item)
                            <tr>
                                <td>{{($item->user->profile ? $item->user->profile->full_name : $item->user->email)}}</td>
                                <td>{{$item->title}}</td>
                                <td>{{$item->grade_level}}</td>
                                <td>{{$item->level}}</td>
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
                            </tr>
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
</section>

@include('layouts.partials.modal_students')

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
    $('#link-achievement').addClass('active');
    $('#submenu-achievement').addClass('show');
    $('#link-achievement-index').addClass('active');
});
</script>
@endpush