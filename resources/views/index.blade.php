@extends('layouts.master')

@push('css-styles')
<style>
.content-title { font-size: 3em; }
.alert { font-size: .8em; padding: 10px; margin-bottom: 0; }
</style>
@endpush

@section('content')

<section>
    

    <!-- container start -->
    <div class="container-fluid mb-4" style="background: url({{asset('img/materials/board-content.png')}}) #3c6457; background-attachment: fixed;">
        <div class="row align-items-center pt-5 mb-4">
            <div class="col-md-5">
                <div class="text-center">
                    <img src="{{asset('img/materials/teacher.png')}}" class="img-fluid" style="max-height:420px">
                </div>
            </div>
            @auth
            @if(Auth::user()->profile->role != 'student')
            <div class="col-md-7 p-4 text-white">
                <h3 class="d-flex align-items-center gap-2 fs-16 mb-3"><i class="bx bx-key"></i>User confirmation key</h3>
                <div class="mb-3 w-75 input-group">
                    <input type="text" id="confirmation-key" class="form-control" value="40620b4f-cc40-402a-8b7d-b6e48f736031">
                    <button class="input-group-text d-flex align-items-center gap-2" onclick="copy()"><i class="bx bx-copy"></i>Copy to clipboard</button>
                </div>
                <p class="fs-8 fst-italic text-light mb-0">*) Share this key to any teacher who wish to confirm their account</p>
            </div>
            @else
            <div class="col-md-7 p-4 text-white">
                <h3 class="dispay-5 fs-24 mb-3">Be a Part of The School's Hall of Fame</h3>
                <p class="fs-12 w-50 m-0"><a href="/achievement/create" class="flex-center gap-2 btn btn-outline-light fw-500"><i class="bx bx-medal"></i>Submit Your Achievement</a></p>
            </div>
            @endif
            @endauth
            @guest
            <div class="col-md-7 p-4 text-white">
                <h3 class="dispay-5 fs-24 mb-3">Be a Part of The School's Hall of Fame</h3>
                <p class="fs-12 w-50 m-0"><span role="button" onclick="modal_register_show()" class="flex-center gap-2 btn btn-outline-light fw-500"><i class="bx bx-log-in"></i>Sign up</span></p>
            </div>
            @endguest
        </div>
    </div>
    <!-- container end -->

    @if(count($alerts) > 0 && isset($alerts['general']))
    <!-- Alerts general start -->
    <div class="container pt-3 my-3">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-warning mb-3">
                    <ul class="list-unstyled m-0">
                    @foreach($alerts['general'] as $item)
                    <li>{!! $item !!}</li>
                    @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Alerts general end -->
    @endif

    <!-- student achievement start -->
    <div class="container pt-3 my-3">
        <div class="row">
            @if(count($alerts) > 0 && isset($alerts['achievement']))
            <div class="col-md-12">
                <div class="alert alert-warning mb-3">
                    <ul class="list-unstyled m-0">
                    @foreach($alerts['achievement'] as $item)
                    <li>{!! $item !!}</li>
                    @endforeach
                    </ul>
                </div>
            </div>
            @endif
            <div class="col-md-12 text-center">
                <h1 class="display-5 content-title">Student Achievement</h1>
                <div class="flex-center">
                    <hr class="w-25 border border-1 border-primary">
                </div>
            </div>
        </div>
        <div class="row">
            @forelse($achievements as $item)
            @if($item->user->profile)
            <?php 
                $current_grade = $item->user->profile->grade + date('Y', time()) - $item->user->profile->year_join; 
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
            <div class="achievement-item col-md-4 p-3">
                <a href="/achievement/{{$item->id}}">
                <div class="achievement-body border rounded hover-shadow">
                    <div class="bg-primary p-3 rounded-top text-white d-flex align-items-center flex-wrap gap-3">
                        @if($item->user->picture)
                        <img src="{{asset('img/profiles/'.$item->user->picture)}}" alt="" class="rounded-circle shadow mh-80">
                        @else
                        <img src="{{asset('img/profiles/user.jpg')}}" alt="" class="rounded-circle shadow mh-80">
                        @endif
                        <div class="col">
                            <h5 class="fw-300 mb-1">{{$item->user->profile->full_name}}</h5>
                            <p class="fs-8 m-0">{{$grade_level}}</p>
                        </div>
                    </div>
                    <div class="px-2 pt-1">
                        <p class="text-end fs-7 text-muted m-0">{{date('j F Y', strtotime($item->created_at))}}</p>
                    </div>
                    <div class="px-3 pt-1 pb-3 rounded-bottom">
                        <p class="fs-11 mb-1">{{$item->attainment.' '.$item->competition}}</p>
                        <p class="fs-8 fw-300 flex-start gap-2 mb-2">
                            <span>{{$item->grade_level}}</span> | <span>{{$item->level}}</span>
                        </p>
                        <p class="m-0 flex-start gap-2 fs-8 text-muted">
                            <span class="flex-start gap-2"><i class="bx bx-message"></i>{{count($item->comment)}}</span>
                        </p>
                    </div>
                </div>
                </a>
            </div>
            @endif
            @empty
            <p class="text-center fs-9 fst-italic text-muted">No achievement has been submitted yet</p>
            @endforelse
        </div>
        <div class="row">
            <div class="col-md-12 flex-center py-3">
                <p class="m-0 fs-10 fst-italic"><a href="/achievement" class="hover-underline">More achievements &raquo;</a></p>
            </div>
        </div>
    </div>
    <!-- student achievement end -->
    
    <!-- container start -->
    <div class="container-fluid mb-4" style="background: #f9f1ed; background-attachment: fixed; color:#404040">
        <div class="row align-items-center py-5 mb-4">
            <div class="col-md-5 mb-3">
                <div class="text-center">
                    <img src="{{asset('img/materials/project.png')}}" class="img-fluid" style="max-height:320px">
                </div>
            </div>
            <div class="col-md-7 px-5 mb-3">
                <h3 class="fs-32 ls-1">STEAM</h3>
                <p class="fs-10"><b>STEAM</b> stands for Science, Technology, Engineering, Arts, and Mathematics. It's an educational approach that integrates these five disciplines into a cohesive learning paradigm. STEAM education emphasizes cross-disciplinary learning and encourages students to apply knowledge and skills from each of these areas to solve complex, real-world problems.</p>
                <p class="fs-10">A <b>STEAM project</b> is an educational initiative that embodies these principles, offering students hands-on experiences and creative opportunities to apply science, technology, engineering, arts, and mathematics in innovative ways. These projects encourage students to explore, experiment, and innovate, preparing them for the demands of the 21st century where diverse skill sets and adaptability are highly valued.</p>
            </div>
        </div>
    </div>
    <!-- container end -->

    <!-- steam projects start -->
    <div class="container pt-3 my-3">
        <div class="row py-4 mb-4">
            <div class="col-md-12 text-center mb-3">
                <h1 class="display-5 content-title">STEAM Project</h1>
                <div class="flex-center">
                    <hr class="w-25 border border-1 border-primary">
                </div>
            </div>
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
            <p class="text-center text-muted fst-italic">No project has been made yet</p>
            @endforelse
        </div>
        <div class="row">
            <div class="col-md-12 flex-center py-3">
                <p class="m-0 fs-10 fst-italic"><a href="/steamProject" class="hover-underline">More projects &raquo;</a></p>
            </div>
        </div>
    </div>
    <!-- steam projects end -->

</section>

@endsection

@push('scripts')
<script type="text/javascript">
@if(Auth::check() && Auth::user()->profile->role != 'student')
// copy to clipboard start 
const copy = () => {
    let copyText = document.getElementById('confirmation-key');
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);
    infoMessage('Confirmation key copied');
};
@endif
</script>
@endpush