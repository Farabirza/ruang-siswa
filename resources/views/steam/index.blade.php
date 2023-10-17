@extends('layouts.master')

@push('css-styles')
<link href="{{ asset('/vendor/datatables/datatables.min.css') }}" rel="stylesheet">
<link href="{{ asset('/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
<style>
table { font-size: .8em; }
td { vertical-align: middle; }
.alert { font-size: .8em; padding: 10px; }

.steam-item-cover > img { height: 100%; }
</style>
@endpush

@section('content')
<section>
    <div class="container pt-3 my-3">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1 class="display-5 content-title">Steam Projects</h1>
                <div class="flex-center">
                    <hr class="w-25 border border-1 border-primary">
                </div>
            </div>
        </div>
        <!-- steam projects start -->
        <div class="row py-4 mb-4">
            @if(Auth::check() && Auth::user()->profile->role == 'student')
            <div class="col-md-12 bg-light border rounded p-4 mb-3 d-flex flex-wrap">
                <div class="col-md-12 ps-2">
                    <h3 class="text-primary flex-between gap-3">
                        <span class="flex-start gap-2"><i class="bx bx-file"></i>My Projects</span>
                        <hr class="col">
                        <a href="/steamProject/create"><i class="bx bx-plus-circle popper" title="New project"></i></a>
                    </h3>
                </div>
                @forelse(Auth::user()->steamMember as $item)
                <div class="col-md-3 p-2">
                    <div class="rounded border bg-white">
                        <div class="p-3">
                            <h5 class="text-muted fs-7 mb-1">{{date('j F Y', strtotime($item->steamProject->created_at))}}</h5>
                            <p class="text-primary fs-12 mb-1">
                                <a href="/steamProject/{{$item->steamProject->id}}">{{$item->steamProject->title}}</a>
                            </p>
                            <p class="fs-8 m-0">{{$item->steamProject->steamCategory->name}}</p>
                        </div>
                    </div>
                </div>
                @empty
                <p class="fst-italic mt-3 fs-9">You are not a part of any project</p>
                @endforelse
            </div>
            @endif
            @forelse($steamProjects as $item)
            <div class="col-md-3 p-2">
                <a href="/steamProject/{{$item->id}}">
                <div class="rounded border hover-shadow bg-white">
                    <div class="rounded-top" style="height:240px">
                        <div class="d-flex justify-content-center overflow-hidden" style="height:240px; background:whitesmoke">
                            @if($item->image)
                            <img src="{{asset('img/steam/'.$item->image)}}">
                            @else
                            <img src="{{asset('img/materials/noimage.jpg')}}">
                            @endif
                        </div>
                    </div>
                    <div class="p-3">
                        <h5 class="text-end text-muted fs-7 mb-1">{{date('j F Y', strtotime($item->created_at))}}</h5>
                        <p class="text-primary fs-12 mb-1">{{$item->title}}</p>
                        <p class="fs-8 mb-2">{{$item->steamCategory->name}}</p>
                        <p class="fs-8 text-secondary mb-4">
                            @for($i = 0; $i < count($item->steamMember); $i++)
                            @if($i < count($item->steamMember)-1)
                            <span>{{$item->steamMember[$i]->user->profile->full_name}},</span>
                            @else
                            <span>{{$item->steamMember[$i]->user->profile->full_name}}</span>
                            @endif
                            @endfor
                        </p>
                        <div class="flex-start flex-wrap gap-4 fs-9 text-secondary">
                            <div class="flex-start gap-2"><i class="bx bx-message"></i>{{count($item->comment)}}</div>
                            <div class="flex-start gap-2"><i class="bx bx-file"></i>{{count($item->steamLogBook)}}</div>
                        </div>
                    </div>
                </div>
                </a>
            </div>
            @empty
            <p class="fs-9 text-muted text-center">No project has been made yet</p>
            @endforelse
        </div>
        <!-- steam projects end -->

        <!-- steam log books start -->
        <div class="row rounded shadow bg-white p-4 mb-5">
            <h3 class="display-5 fs-18 text-primary mb-2">STEAM Log Book</h3>
            <div class="table-container">
                <table id="table-steamLogBook" class="table table-striped">
                    <thead>
                        <th>Date</th>
                        <th>Log title</th>
                        <th>Project title</th>
                        <th>Project category</th>
                    </thead>
                    <tbody>
                        @forelse($steamLogBooks->sortByDesc('created_at') as $item)
                        <tr>
                            <td>{{date('j F Y', strtotime($item->created_at))}}</td>
                            <td><a href="/steamLogBook/{{$item->id}}" class="hover-primary">{{$item->title}}</a></td>
                            <td><a href="/steamProject/{{$item->steamProject->id}}" class="hover-primary">{{$item->steamProject->title}}</a></td>
                            <td>{{$item->steamProject->steamCategory->name}}</td>
                        </tr>
                        @empty
                        <tr>
                            <td class="text-center text-muted" colspan="4">Empty</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- steam log books end -->

        <!-- steam categories start -->
        <div class="row rounded shadow bg-white pb-3 mb-4">
            <div class="bg-primary p-4 rounded-top mb-4 text-white flex-between gap-3">
                <h3 class="m-0 fs-18 fw-500">STEAM Project Categories</h3>
                @if(Auth::check() && Auth::user()->profile->role != 'student')
                <i class="bx bx-plus-circle fs-18" role="button" onclick="modalSteamCategory('post')"></i>
                @endif
            </div>
            <?php $i = 1; ?>
            @forelse($steamCategories as $item)
            <input type="hidden" name="steamCategory_id" id="steamCategory_id-{{$i}}" value="{{$item->id}}">
            <div id="steamCategory-item-{{$i}}" class="col-md-12 d-flex align-items-center flex-remove-md gap-3 py-3 mb-3">
                <div class="col-md-6 px-4 text-center">
                    @if($item->image)
                    <img id="steamCategory-image-{{$i}}" src="{{asset('img/steam/categories/'.$item->image)}}" class="rounded border img-fluid" style="max-height:320px">
                    @else
                    <img id="steamCategory-image-{{$i}}" src="{{asset('img/materials/noimage.jpg')}}" class="rounded border img-fluid" style="max-height:320px">
                    @endif
                </div>
                <div class="col-md-6 px-4">
                    <p class="flex-between gap-3">
                        <span id="steamCategory-name-{{$i}}" class="fw-500 fs-16 text-primary">{{$item->name}}</span>
                        @if(Auth::check() && Auth::user()->profile->role != 'student')
                        <i class="bx bx-edit-alt text-muted hover-primary" role="button" onclick="modalSteamCategory('update', '{{$i}}')"></i>
                        @endif
                    </p>
                    <p id="steamCategory-description-{{$i}}" class="fs-9" style="color:#404040">{{$item->description}}</p>
                    <div class="flex-start gap-3">
                        <span class="fs-9">Projects : {{count($item->steamProject)}}</span>
                    </div>
                </div>
            </div>
            <?php $i++; ?>
            @empty
            <p class="fst-italic">No project category has been made yet</p>
            @endforelse
        </div>
        <!-- steam categories end -->
    </div>
</section>

@endsection

@if(Auth::check() && Auth::user()->profile->role != 'student')
@include('layouts.partials.modal_steamCategory')
@endif

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
    new DataTable('#table-steamLogBook', {
        pageLength: 50,
        fixedColumns: true,
        ordering: true,
        searching: true,
    });
});
</script>
@endpush