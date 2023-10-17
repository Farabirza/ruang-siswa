@extends('layouts.master')

@push('css-styles')
<link href="{{ asset('/vendor/datatables/datatables.min.css') }}" rel="stylesheet">
<link href="{{ asset('/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
<style>
table { font-size: .8em; }
.alert { font-size: .9em; padding: 10px; margin-bottom: 0; }
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
                    <li class="breadcrumb-item"><a href="/steamProject/{{$steamLogBook->steamProject->id}}">{{$steamLogBook->steamProject->title}}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{$steamLogBook->title}}</li>
                </ol>
            </nav>
        </div>
        <!-- breadcrumb end -->
    </div>

    <!-- container start -->
    <div class="container mb-4">
        <div class="row bg-white rounded shadow">
            <div class="col-md-12 bg-primary rounded-top text-light">
                <h3 class="flex-between gap-3 display-5 fs-18 p-4 mb-0">
                    <span>{{$steamLogBook->title}}</span> 
                    @if($isMember)
                    <a href="/steamLogBook/{{$steamLogBook->id}}/edit"><i class="bx bx-edit-alt"></i></a>
                    @endif
                </h3>
            </div>
            <div class="col-md-12 p-4">
                <p class="text-end text-muted mb-2 fs-9">{{date('j F Y', strtotime($steamLogBook->created_at))}}, by <a href="/profile/{{$steamLogBook->user->profile->id}}" class="hover-primary">{{$steamLogBook->user->profile->full_name}}</a></p>
                {!! $steamLogBook->content !!}
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
const lightbox = GLightbox({
    selector: '.glightbox',
});
$(document).ready(function() {
    // new DataTable('#table-steamLogBook', {
    //     pageLength: 50,
    //     fixedColumns: true,
    //     ordering: true,
    //     searching: true,
    // });
    $('#link-steam').addClass('active');
    $('#submenu-steam').addClass('show');
});
</script>
@endpush