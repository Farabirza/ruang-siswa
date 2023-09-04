@extends('layouts.master')

@push('css-styles')
<style>
.content-title {
    font-size: 3em;
}
</style>
@endpush

@section('content')

<section>

    <!-- student achievement start -->
    <div class="container pt-3 my-3">
        <div class="row">
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
                            <p class="fw-300 fs-8 mb-1">{{$item->user->email}}</p>
                            <p class="fs-8 m-0">{{$grade_level}}</p>
                        </div>
                    </div>
                    <div class="px-2 pt-1">
                        <p class="text-end fs-7 text-muted m-0">{{date('j F Y', strtotime($item->created_at))}}</p>
                    </div>
                    <div class="px-3 pt-1 pb-3 rounded-bottom">
                        <p class="fs-11 mb-1">{{$item->title}}</p>
                        <p class="fs-8 fw-300 flex-start gap-2 mb-1">
                            <span>{{$item->grade_level}}</span> | <span>{{$item->level}}</span>
                        </p>
                    </div>
                </div>
                </a>
            </div>
            @endif
            @empty
            @endforelse
        </div>
        <div class="row">
            <!-- pagination start -->
            <div class="col-md-12 d-flex justify-content-center">
            {{ $achievements->onEachSide(3)->appends($_GET)->links() }}
            </div>
            <!-- pagination end -->
        </div>
    </div>
    <!-- student achievement end -->

</section>

@endsection

@push('scripts')
<script type="text/javascript">
</script>
@endpush