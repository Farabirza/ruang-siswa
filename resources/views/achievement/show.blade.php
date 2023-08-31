@extends('layouts.master')

@push('css-styles')
<link href="{{ asset('/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
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
                    <li class="breadcrumb-item"><a href="/achievement">Achievement</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{$achievement->title}}</li>
                </ol>
            </nav>
        </div>
        <!-- breadcrumb end -->
    </div>

    <!-- container start -->
    <div class="container mb-3">
        <div class="row bg-white p-3 rounded shadow">
            <div class="col-md-12 py-2">
                <h3 class="d-flex align-items-center justify-content-between gap-2 fs-16 mb-4"><span class="flex-start gap-2"><i class="bx bx-medal"></i>Achievement</span></h3>
                <div class="d-flex align-items-center flex-remove-md gap-3 mb-3">
                    <div class="mb-2 text-center">
                        @if($user->picture)
                        <img src="{{ asset('img/profiles/'.$user->picture) }}" alt="" class="rounded shadow" style="max-height:240px">
                        @else
                        <img src="{{ asset('img/profiles/user.jpg') }}" alt="" class="rounded shadow" style="max-height:240px">
                        @endif
                        @if($user->profile)
                        <h5 class="text-primary fs-10 ls-1 mt-3 mb-1">{{$user->profile->full_name}}</h5>
                        @endif
                        <p class="fs-8 fst-italic">{{$user->email}}</p>
                    </div>
                    <div class="col mb-2">
                        <p class="text-primary mb-1 flex-start gap-2"><i class="bx bx-file"></i>Detail</p>
                        <table class="table">
                            <tr>
                                <td>Title</td>
                                <td class="px-2">:</td>
                                <td>{{$achievement->title}}</td>
                            </tr>
                            <tr>
                                <td>Level</td>
                                <td class="px-2">:</td>
                                <td>{{$achievement->level}}</td>
                            </tr>
                            <tr>
                                <td>Grade</td>
                                <td class="px-2">:</td>
                                <td>{{$achievement->grade_level}}</td>
                            </tr>
                            <tr>
                                <td>Year</td>
                                <td class="px-2">:</td>
                                <td>{{$achievement->year}}</td>
                            </tr>
                            <tr>
                                <td>Organizer</td>
                                <td class="px-2">:</td>
                                @if($achievement->url)
                                <td><a href="//{{$achievement->url}}" class="hover-primary" target="_blank">{{$achievement->organizer}}</a></td>
                                @else
                                <td>{{$achievement->organizer}}</td>
                                @endif
                            </tr>
                            <tr>
                                <td>Certificate</td>
                                <td class="px-2">:</td>
                                @if($achievement->certificate_pdf)
                                <td><a href="{{asset('img/certificate/pdf/'.$achievement->certificate_pdf)}}" target="_blank" class="hover-primary">{{$achievement->certificate_pdf}}</a></td>
                                @else
                                <td>-</td>
                                @endif
                            </tr>
                        </table>
                    </div>
                </div>
                @if($achievement->certificate_image)
                <p class="text-primary mb-2 flex-start gap-2"><i class="bx bx-image-alt"></i>Certificate</p>
                <div class="flex-start gap-3">
                    <a href="{{asset('img/certificate/'.$achievement->certificate_image)}}" class="glightbox">
                        <figure class="hover-shine"><img src="{{asset('img/certificate/'.$achievement->certificate_image)}}" style="max-height:320px" class="border"></figure>
                    </a>
                    @else
                    <p class="text-muted mb-0">No certificate submitted</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    <!-- container end -->

</section>

@endsection

@push('scripts')
<script src="{{ asset('/vendor/glightbox/js/glightbox.min.js') }}"></script>
<script type="text/javascript">
const lightbox = GLightbox({
    selector: '.glightbox',
});
</script>
@endpush