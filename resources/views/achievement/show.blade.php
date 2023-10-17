@extends('layouts.master')

@push('css-styles')
<link href="{{ asset('/vendor/datatables/datatables.min.css') }}" rel="stylesheet">
<link href="{{ asset('/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
<style>
table { font-size: .8em; }
.alert { font-size: .9em; padding: 10px; margin-bottom: 0; }

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
                    <li class="breadcrumb-item"><a href="/achievement">Achievement</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{$achievement->attainment.' '.$achievement->competition}}</li>
                </ol>
            </nav>
        </div>
        <!-- breadcrumb end -->
    </div>

    <!-- container start -->
    <div class="container">
        <div class="row">
            <div class="col-md-12 p-0 mb-3">
                @if($achievement->confirmed == 0)
                <div class="alert alert-warning flex-between gap-3">
                    <p class="fs-9 m-0">This achievement data is not published yet until confirmation by a teacher</p>
                    @if(Auth::check() && Auth::user()->profile->role != 'student')
                    <a href="/achievement/{{$achievement->id}}/confirm" class="popper" title="Confirm"><i class="bx bx-check bx-border-circle btn-outline-primary p-1"></i></a>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
    <!-- container end -->

    <!-- container start -->
    <div class="container mb-4">
        <div class="row bg-white rounded shadow">
            <div class="col-md-12 d-flex align-items-center flex-wrap gap-3 p-4 rounded-top text-white" style="background:#2196f4">
                <div class="px-2">
                    @if($user->picture)
                    <img src="{{ asset('img/profiles/'.$user->picture) }}" alt="" class="rounded-circle shadow" style="max-height:180px">
                    @else
                    <img src="{{ asset('img/profiles/user.jpg') }}" alt="" class="rounded-circle shadow" style="max-height:180px">
                    @endif
                </div>
                <div class="px-2">
                    <h5 class="fs-24 ls-1 mb-2"><a href="/profile/{{$user->profile->id}}">{{$user->profile->full_name}}</a></h5>
                    <?php 
                        $current_grade = $user->profile->grade + date('Y', time()) - $user->profile->year_join; 
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
                    <p class="fs-10 m-0">{{$grade_level}}</p>
                </div>
            </div>
            <div class="col-md-12 p-4">
                <p class="text-end fs-8 text-muted m-0">{{(date('j F Y', strtotime($achievement->created_at)))}}</p>
                <div class="d-flex align-items-center flex-remove-md gap-3 mb-3">
                    <table class="table m-0">
                        <tr>
                            <td class="text-primary fw-500">Title</td>
                            <td class="px-2">:</td>
                            <td class="fw-bold fs-11 ls-1">{{$achievement->attainment.' '.$achievement->competition}}</td>
                        </tr>
                        <tr>
                            <td class="text-primary fw-500">Regional level</td>
                            <td class="px-2">:</td>
                            <td>{{$achievement->level}}</td>
                        </tr>
                        <tr>
                            <td class="text-primary fw-500">Grade level</td>
                            <td class="px-2">:</td>
                            <td>{{$achievement->grade_level}}</td>
                        </tr>
                        <tr>
                            <td class="text-primary fw-500">Subject</td>
                            <td class="px-2">:</td>
                            <td>{{$achievement->subject->name}}</td>
                        </tr>
                        <tr>
                            <td class="text-primary fw-500">Competition date</td>
                            <td class="px-2">:</td>
                            <td>
                                <span>{{($achievement->start_date ? date('j F Y', strtotime($achievement->start_date)) : '-')}}</span>
                                @if($achievement->end_date != null && $achievement->end_date > $achievement->start_date)
                                - <span>{{date('j F Y', strtotime($achievement->end_date))}}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-primary fw-500">Organizer</td>
                            <td class="px-2">:</td>
                            @if($achievement->url)
                            <td><a href="{{$achievement->url}}" class="hover-primary" target="_blank">{{$achievement->organizer}}</a></td>
                            @else
                            <td>{{($achievement->organizer) ? $achievement->organizer : '-'}}</td>
                            @endif
                        </tr>
                        <tr>
                            <td class="text-primary fw-500">Certificate</td>
                            <td class="px-2">:</td>
                            @if($achievement->certificate_pdf)
                            <td><a href="{{asset('img/certificate/pdf/'.$achievement->certificate_pdf)}}" target="_blank" class="hover-primary">{{$achievement->certificate_pdf}}</a></td>
                            @else
                            <td>-</td>
                            @endif
                        </tr>
                    </table>
                </div>
                @if($achievement->description)
                <p class="text-primary mb-2 flex-start gap-2"><i class="bx bx-align-left"></i>Description</p>
                <p class="fs-9 mb-3">{{$achievement->description}}</p>
                @endif
                @if($achievement->certificate_image)
                <div class="mb-3">
                    <p class="text-primary mb-2 flex-start gap-2"><i class="bx bx-image-alt"></i>Certificate image</p>
                    <div class="flex-start gap-3">
                        <a href="{{asset('img/certificate/'.$achievement->certificate_image)}}" class="glightbox">
                            <figure class="hover-shine"><img src="{{asset('img/certificate/'.$achievement->certificate_image)}}" style="max-height:320px" class="border"></figure>
                        </a>
                    </div>
                </div>
                @endif
                @if(count($achievement->image) > 0)
                <div class="mb-3">
                    <p class="text-primary mb-2 flex-start gap-2"><i class="bx bx-images"></i>Photo</p>
                    <div class="flex-start flex-wrap gap-3">
                        @foreach($achievement->image as $item)
                        <a href="{{asset('img/photos/'.$item->name)}}" class="glightbox_images" data-glightbox="title:{{$item->caption}};">
                            <img src="{{asset('img/photos/'.$item->name)}}" class="img-thumbnail" style="max-height:240px;">
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
                @if(Auth::check() && (Auth::user()->profile->role != 'student' || $achievement->user_id == Auth::user()->id))
                <div class="mt-3 flex-start gap-3">
                    <hr class="col">
                    <a href="/achievement/{{$achievement->id}}/delete" class="btn btn-sm btn-outline-danger flex-start gap-2 btn-warn" data-warning="Do you wish to delete this achievement data?"><i class="bx bx-trash-alt"></i>Delete</a>
                    <a href="/achievement/{{$achievement->id}}/edit" class="btn btn-sm btn-success flex-start gap-2"><i class="bx bx-edit-alt"></i>Edit</a>
                </div>
                @endif
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
                    @forelse($achievement->comment as $item)
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
                        @if(Auth::check() && (Auth::user()->profile->role != 'student' || Auth::user()->id == $item->user_id || Auth::user()->id == $achievement->user_id))
                        <div class="flex-end gap-3 fs-8">
                            <a href="/comment/{{$item->id}}/delete" class="hover-danger flex-start gap-1 btn-warn" data-warning="Do you wish to delete this comment?"><i class="bx bx-trash-alt"></i>Remove</a>
                        </div>
                        @endif
                    </div>
                    @empty
                    <p class="m-0">No comment has been made yet</p>
                    @endforelse
                </div>
                <!-- comment container end -->
                @auth
                <!-- form comment start -->
                <form action="/achievement/{{$achievement->id}}/comment" method="post" class="m-0">
                @csrf
                <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                <div class="m-0">
                    <textarea name="content" id="achievement-content" class="form-control mb-3" style="min-height:80px;" placeholder="Congratulation!" required>{{ old('content') }}</textarea>
                    @error('content')
                    <p class="alert alert-danger">{{$message}}</p>
                    @enderror
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
    
    <!-- container start -->
    <div class="container mb-4">
        <div class="row bg-white p-3 rounded shadow">
            <div class="col-md-12 py-2">
                <h3 class="d-flex align-items-center justify-content-between gap-2 fs-16 mb-4"><span class="flex-start gap-2"><i class="bx bx-list-ul"></i>{{$user->profile->full_name}}'s achievements</span></h3>
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
                        <tr><td colspan="6" class="text-center fst-italic">empty</td></tr>
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
<script src="{{ asset('/vendor/glightbox/js/glightbox.min.js') }}"></script>
<script type="text/javascript">
const lightbox_images = GLightbox({
    selector: '.glightbox_images',
});
const lightbox = GLightbox({
    selector: '.glightbox',
});
$(document).ready(function() {
    new DataTable('#table-achievements', {
        pageLength: 100,
        fixedColumns: true,
        ordering: true,
        searching: true,
    });
    $('#link-achievement').addClass('active');
    $('#submenu-achievement').addClass('show');
});
</script>
@endpush