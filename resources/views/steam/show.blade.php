@extends('layouts.master')

@push('css-styles')
<link href="{{ asset('/vendor/datatables/datatables.min.css') }}" rel="stylesheet">
<link href="{{ asset('/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
<style>
table { font-size: .8em; }
.alert { font-size: .9em; padding: 10px; margin-bottom: 0; }

.steam-controller {
    right: 20px;
    top: 20px;
    position: absolute;
} .steam-controller > a { border: 1px solid #fff; transition: .2s ease-in-out; }

.steam-cover {
    position: relative;
    @if($steam->image)
    background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url("{{asset('img/steam/'.$steam->image)}}") top center;
    @else
    background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url("{{asset('img/materials/noimage.jpg')}}") top center;
    @endif
    background-size: cover;
    background-position: fixed;
    min-height: 280px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 80px 20px;
}

.comment-content {
  background-color: #f1f1f1;
  color: #444;
  width: 100%;
  padding: 10px;
  border-radius: 4px;
  margin: 5px 0 0 10px;
  position: relative;
}
.comment-content::after, .comment-content::before {
  border: solid transparent;
  border-right-color: #f1f1f1;
  content: " ";
  height: 0;
  pointer-events: none;
  position: absolute;
  right: 100%;
  top: 15px;
  width: 0;
}
.comment-content::after {
  border-width: 5px;
  margin-top: -5px;
}
.comment-content::before {
  border-width: 6px;
  margin-top: -6px;
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
                    <li class="breadcrumb-item"><a href="/steamProject">STEAM Project</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{$steam->title}}</li>
                </ol>
            </nav>
        </div>
        <!-- breadcrumb end -->
    </div>

    <!-- container start -->
    <div class="container mb-4">
        <div class="row bg-white rounded shadow">
            <div class="steam-cover rounded-top">
                @if(Auth::check() && (Auth::user()->profile->role != 'student' || $isMember == true))
                <div class="steam-controller flex-end gap-3">
                    @if(Auth::user()->profile->role != 'student')
                    <a href="/steamProject/{{$steam->id}}/delete" class="btn-outline-light rounded-circle py-1 px-2 btn-warn popper" title="Delete this project" data-warning="Do you wish to delete this project? All the data associated with it will be lost forever"><i class="bx bx-trash-alt"></i></a>
                    @endif
                    <a href="/steamProject/{{$steam->id}}/edit" class="btn-outline-light rounded-circle py-1 px-2 popper" title="Edit data"><i class="bx bx-edit-alt"></i></a>
                    @if($isMember == true)
                    <a href="/steamProject/{{$steam->id}}/logbook/create" class="btn-outline-light rounded-circle py-1 px-2 popper" title="New log book"><i class="bx bxs-file-plus"></i></a>
                    @endif
                </div>
                @endif
                <div class="text-white text-center">
                    <h3 class="display-5 mb-3">{{$steam->title}}</h3>
                    <p><a href="/steamProject/{{$steam->id}}" class="bg-white text-dark fs-9 px-4 py-1 rounded-pill">{{$steam->steamCategory->name}}</a></p>
                    <div class="flex-center flex-wrap gap-4 fs-12">
                        <div class="flex-start gap-2"><i class="bx bx-group"></i>{{count($steam->steamMember)}}</div>
                        <div class="flex-start gap-2"><i class="bx bx-message"></i>{{count($steam->comment)}}</div>
                        <div class="flex-start gap-2"><i class="bx bx-file"></i>{{count($steam->steamLogBook)}}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 p-4 mb-3">
                <p class="text-end text-muted fs-8">{{date('d F Y', strtotime($steam->created_at))}}</p>
                <div class="mb-3">
                    <p class="text-primary fw-500 mb-2">About this project :</p>
                    <p class="fs-9">{!! ($steam->description) ? $steam->description : "No description" !!}</p>
                </div>
                <!-- members start -->
                <div class="mb-3">
                    <p class="text-primary fw-500 mb-2">Members :</p>
                    <div class="d-flex flex-wrap align-items-center gap-3">
                        @if(Auth::check() && (Auth::user()->profile->role != 'student' || $isMember == true))
                        <div class="rounded-circle border border-2 border-primary btn-outline-primary flex-center" style="height:80px;width:80px;" role="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-steamMember" aria-controls="offcanvas-steamMember">
                            <i class="bx bx-user-plus fs-14 fw-bold"></i>
                        </div>
                        @endif
                        <div id="container-steamMember-items" class="d-flex flex-wrap align-items-center gap-3">
                        @if(count($steam->steamMember) > 0)
                        <?php $i = 1; ?>
                            @foreach($steam->steamMember as $item)
                            <div id="steamMember-item-{{$i}}" class="text-center">
                                <img src="{{($item->user->picture ? asset('img/profiles/'.$item->user->picture) : asset('img/profiles/user.jpg'))}}" id="steamMember-{{$i}}" class="rounded-circle border hover-grow mb-2 popper" style="height:80px" data-title="{{$item->user->profile->full_name}}" data-user_id="{{$item->user->id}}" data-profile_id="{{$item->user->profile->id}}" role="button" onclick="showSteamMember('{{$i}}')">
                                <p class="fs-8 m-0 text-wrap" style="max-width:100px">{{$item->user->profile->full_name}}</p>
                            <?php $i++; ?>
                            </div>
                            @endforeach
                        @endif
                        </div>
                    </div>
                    <p id="alert-members" class="alert alert-danger d-none"></p>
                </div>
                <!-- members end -->
                <!-- STEAM log start -->
                <div class="mb-3">
                    <p class="text-primary fw-500 mb-3 flex-between gap-3">
                        <span>STEAM log book :</span>
                        @if($isMember == true)
                        <a href="/steamProject/{{$steam->id}}/logbook/create" class="hover-underline d-flex align-items-center gap-2">Add new log<i class="bx bx-plus"></i></a>
                        @endif
                    </p>
                    <div class="table-container">
                        <table id="table-steamLogBook" class="table table-striped">
                            <thead>
                                <th>Date</th>
                                <th>Title</th>
                            </thead>
                            <tbody>
                                @if(count($steam->steamLogBook) > 0)
                                @foreach($steam->steamLogBook as $item)
                                <tr>
                                    <td>{{date('Y/m/d', strtotime($item->created_at))}}</td>
                                    <td><a href="/steamLogBook/{{$item->id}}" class="hover-primary">{{$item->title}}</a></td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- STEAM log end -->
            </div>
        </div>
    </div>
    <!-- container end -->
    

    <!-- container start -->
    <div class="container mb-4">
        <div class="row bg-white p-3 rounded shadow">
            <div class="col-md-12 py-2">
                <h3 class="d-flex align-items-center justify-content-between gap-2 fs-16 mb-4"><span class="flex-start gap-2"><i class="bx bx-message"></i>Comment</span></h3>
                <!-- comment container start -->
                <div id="container-comments" class="mb-3">
                    @forelse($steam->comment as $item)
                    <div class="mb-3">
                        <div class="flex-start gap-1">
                            @if($item->user->picture)
                            <img src="{{asset('img/profiles/'.$item->user->picture)}}" alt="" class="rounded-circle shadow" style="height:50px">
                            @else
                            <img src="{{asset('img/profiles/user.jpg')}}" alt="" class="rounded-circle shadow" style="height:50px">
                            @endif
                            <div class="comment-content">
                                <div class="flex-between gap-3 mb-2 fs-8 text-muted">
                                    <div class="flex-start gap-3">
                                        <a href="/profile/{{$item->user->profile->id}}" class="hover-primary">{{($item->user->profile ? $item->user->profile->full_name : $item->user->email)}}</a>
                                    </div>
                                    <span class="">{{date('j F Y', strtotime($item->created_at))}}</span>
                                </div>
                                <p class="m-0 fs-9">{{$item->content}}</p>
                            </div>
                        </div>
                        @if(Auth::check() && (Auth::user()->profile->role != 'student' || Auth::user()->id == $item->user_id || $isMember == true))
                        <div class="flex-end gap-3 fs-8">
                            <a href="/comment/{{$item->id}}/delete" class="hover-danger flex-start gap-1 btn-warn" data-warning="Do you wish to delete this comment?"><i class="bx bx-trash-alt"></i>Remove</a>
                        </div>
                        @endif
                    </div>
                    @empty
                    <p class="m-0">No one has commented yet</p>
                    @endforelse
                </div>
                <!-- comment container end -->
                @auth
                <!-- form comment start -->
                <form action="/steamProject/{{$steam->id}}/comment" method="post" class="m-0">
                @csrf
                <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                <div class="m-0">
                    <div class="mb-3">
                        <textarea name="content" id="steam-content" class="form-control" style="min-height:80px;" placeholder="" required>{{ old('content') }}</textarea>
                        @error('content')
                        <p class="alert alert-danger">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="flex-end">
                        <button type="submit" class="btn btn-primary btn-sm flex-start gap-2"><i class="bx bx-message-alt"></i>Send</button>
                    </div>
                </div>
                </form>
                <!-- form comment end -->
                @endauth
            </div>
        </div>
    </div>
    <!-- container end -->

</section>

@include('layouts.partials.modal_steam')

@endsection

@push('scripts')
<script src="{{ asset('/vendor/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('/vendor/glightbox/js/glightbox.min.js') }}"></script>
<script type="text/javascript">
const lightbox = GLightbox({
    selector: '.glightbox',
});
$(document).ready(function() {
    new DataTable('#table-steamLogBook', {
        pageLength: 50,
        fixedColumns: true,
        ordering: true,
        searching: true,
    });
    $('#link-steam').addClass('active');
    $('#submenu-steam').addClass('show');
});
</script>
@endpush