@extends('layouts.master')

@push('css-styles')
<link href="{{ asset('/vendor/datatables/datatables.min.css') }}" rel="stylesheet">
<link href="{{ asset('/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
<style>
table { font-size: .8em; }
td { vertical-align: middle; }
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
                    <li class="breadcrumb-item">Admin</li>
                    <li class="breadcrumb-item active" aria-current="page">User controller</li>
                </ol>
            </nav>
        </div>
        <!-- breadcrumb end -->
    </div>

    <!-- container start -->
    <div class="container mb-3">
        <div class="row bg-white p-3 rounded shadow">
            <div class="col-md-12">
                <h3 class="d-flex align-items-center gap-2 fs-16 mb-3"><i class="bx bx-group"></i>Users</h3>
                <div class="table-container">
                    <table id="table-users" class="table table-striped">
                        <thead>
                            <th>Email</th>
                            <th>Name</th>
                            <th>Authority</th>
                            <th>Role</th>
                            <th>Registered at</th>
                            <th><i class="bx bx-dots-vertical"></i></th>
                        </thead>
                        <tbody>
                            @foreach($users as $item)
                            <tr>
                                <td>{{$item->email}}</td>
                                @if($item->profile)
                                <td><a href="/profile/{{$item->profile->id}}" class="hover-primary">{{$item->profile->full_name}}</a></td>
                                @else
                                <td>-</td>
                                @endif
                                <td>{{$item->authority->name}}</td>
                                <td>{{($item->profile ? $item->profile->role : '-')}}</td>
                                <td>{{date('Y/m/d', strtotime($item->created_at))}}</td>
                                <td>
                                    <div class="dropdown">
                                        <i class="bx bx-dots-vertical bx-border-circle btn-outline-dark p-1" role="button" data-bs-toggle="dropdown" aria-expanded="false"></i>
                                        <div class="dropdown-menu fs-9">
                                            @if($item->profile)
                                            <a href="/profile/{{$item->profile->id}}" class="hover-primary"><div class="dropdown-item"><i class="bx bx-user"></i>Profile</div></a>
                                            @endif
                                            <div class="dropdown-item" onclick="modalResetPassword('{{$item->id}}', '{{$item->email}}')"><i class="bx bx-key"></i>Reset password</div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- container end -->
</section>

@include('layouts.partials.modal_userController')

@endsection

@push('scripts')
<script src="{{ asset('/vendor/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('/vendor/glightbox/js/glightbox.min.js') }}"></script>
<script type="text/javascript">
// glightbox
const lightbox = GLightbox({
    touchNavigation: true,
    loop: true,
    autoplayVideos: true
});
$(document).ready(function() {
    new DataTable('#table-users', {
        pageLength: 100,
        fixedColumns: true,
        ordering: true,
        searching: true,
    });
});
</script>
@endpush