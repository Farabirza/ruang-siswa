@extends('layouts.master')

@push('css-styles')
<style>
img { max-width: 100%; }
.form-label { color: var(--bs-primary); font-size: .9em; }
.form-note { color: var(--bs-secondary); font-style: italic; font-size: .8em; margin-top: 8px; margin-bottom: 0; }
.alert { font-size: .9em; padding: 10px; margin-top: 10px; margin-bottom: 0; }
@media (max-width:1199px) {
    .col { margin-bottom: 10px; }
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
                    <li class="breadcrumb-item active" aria-current="page">New</li>
                </ol>
            </nav>
        </div>
        <!-- breadcrumb end -->
    </div>

    <!-- container start -->
    <div class="container mb-3">
        <div class="row bg-white p-3 rounded shadow">
            <div class="col-md-12 py-2">
                <h3 class="d-flex align-items-center gap-2 fs-16 mb-3"><i class="bx bx-plus-circle"></i>New achievement</h3>

                <!-- form achievement start -->
                <form action="/achievement" method="post" id="form-achievement" class="m-0" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="achievement-user_id" class="form-label">Student</label>
                    <select name="user_id" id="achievement-user_id" class="form-select form-select-sm">
                        @if(Auth::user()->profile->role == 'student')
                        <option value="{{Auth::user()->id}}" selected>{{Auth::user()->profile->full_name}} | {{Auth::user()->email}}</option>
                        @else
                        @forelse($students as $item)
                        @if(Auth::user()->id == $item->id)
                        <option value="{{$item->id}}" selected>{{$item->profile->full_name}} | {{$item->email}}</option>
                        @else
                        <option value="{{$item->id}}">{{$item->profile->full_name}} | {{$item->email}}</option>
                        @endif
                        @empty
                        <option value="">Empty</option>
                        @endforelse
                        @endif
                    </select>
                </div>
                <div class="mb-3 d-flex flex-remove-md gap-3">
                    <div class="col">
                        <label for="achievement-title" class="form-label">Title</label>
                        <input type="text" name="title" id="achievement-title" class="form-control form-control-sm" placeholder="ex: Juara 1 Olimpiade Sains Nasional" value="{{ old('title') }}" required>
                        <p class="form-note">*) required</p>
                    </div>
                    <div class="col">
                        <label for="achievement-level" class="form-label">Regional level</label>
                        <select name="level" id="achievement-level" class="form-select form-select-sm">
                            <option value="Local">Local</option>
                            <option value="National">National</option>
                            <option value="International">International</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3 d-flex flex-remove-md gap-3">
                    <div class="col">
                        <label for="achievement-grade_level" class="form-label">Grade level</label>
                        <select name="grade_level" id="achievement-grade_level" class="form-select form-select-sm">
                            <option value="Senior High">Senior high</option>
                            <option value="Junior High">Junior high</option>
                            <option value="Elementary">Elementary</option>
                        </select>
                    </div>
                    <div class="col">
                        <label for="achievement-year" class="form-label">Year</label>
                        <input type="number" name="year" id="achievement-year" class="form-control form-control-sm" value="{{ (old('year') ? old('year') : date('Y')) }}" placeholder="ex: {{date('Y')}}">
                        @error('year')
                        <p class="alert alert-danger">{{$message}}</p>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label for="achievement-subject_id" class="form-label">Subject</label>
                    <select name="subject_id" id="achievement-subject_id" class="form-select form-select-sm" required>
                        @forelse($subjects as $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                        @empty
                        @endforelse
                        <option value="-">Other subject</option>
                    </select>
                    <div id="achievement-subject">
                    @if(count($subjects) == 0)
                    <input type="text" name="subject" class="form-control form-control-sm mt-3" placeholder="Input the name of the subject" value="{{old('subject')}}" required>
                    @endif
                    </div>
                </div>
                <div class="mb-3 d-flex flex-remove-md gap-3">
                    <div class="col">
                        <label for="achievement-organizer" class="form-label">Organizer</label>
                        <input type="text" name="organizer" id="achievement-organizer" class="form-control form-control-sm" placeholder="ex: Kementerian Pendidikan RI" value="{{ old('organizer') }}">
                    </div>
                    <div class="col">
                        <label for="achievement-url" class="form-label">URL</label>
                        <input type="text" name="url" id="achievement-url" class="form-control form-control-sm" placeholder="ex: https://pribadidepok.sch.id" value="{{ old('url') }}">
                        <p class="form-note">*) link towards competition or organizer's homepage</p>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="achievement-description" class="form-label">Description</label>
                    <textarea name="description" id="achievement-description" style="min-height:80px" class="form-control form-control-sm" placeholder="Additional information">{{ old('description') }}</textarea>
                </div>
                <div class="mb-3 d-flex flex-remove-md gap-3">
                    <div class="col">
                        <p class="m-0"><label for="achievement-certificate_image" class="form-label">Certificate image</label></p>
                        <img src="{{asset('img/materials/noimage.jpg')}}" id="certificate_image-preview" class="img-thumbnail mb-2" style="max-height:240px">
                        <input type="file" name="certificate_image" id="achievement-certificate_image" class="form-control form-control-sm" accept="image/*" value="{{ old('certificate_image') }}">
                    </div>
                    <div class="col">
                        <p class="m-0"><label for="achievement-certificate_pdf" class="form-label">Certificate PDF</label></p>
                        <img src="{{asset('img/materials/pdf.jpg')}}" id="certificate_pdf-preview" class="img-thumbnail img-grayscale mb-2" style="max-height:240px">
                        <input type="file" name="certificate_pdf" id="achievement-certificate_pdf" class="form-control form-control-sm" accept=".pdf" value="{{ old('certificate_pdf') }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Documentation images</label>
                    <p class="fs-10">Submit achievement data before adding documentation images</p>
                </div>
                <div class="mb-3">
                    <p class="form-note">*) maximum allowed image size is 2 MB. You can go to <a href="https://www.iloveimg.com/resize-image" class="text-primary fw-500" target="_blank">this website</a> to resize your image's size</p>
                </div>
                <div class="flex-end gap-3">
                    <button type="submit" class="btn btn-primary flex-center gap-2"><i class="bx bx-mail-send"></i>Submit</button>
                </div>
                </form>
                <!-- form achievement end -->

            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script type="text/javascript">
    
// file handler
$('input[name="certificate_image"]').change(function(e) {
    if(e.target.files && e.target.files[0]) {
        let file = e.target.files[0];
        const maxAllowedSize = 1 * 1024 * 1024;
        if(file.size > maxAllowedSize) {
            infoMessage('your file exceed the maximum allowed file size');
            return $(this).val('');
        }
    }
    let reader = new FileReader();
    reader.onload = (e) => { 
        $('#certificate_image-preview').attr('src', e.target.result);
    }
    reader.readAsDataURL(this.files[0]); 
});
$('input[name="certificate_pdf"]').change(function(e) {
    if(e.target.files && e.target.files[0]) {
        let file = e.target.files[0];
        const maxAllowedSize = 1 * 1024 * 1024;
        if(file.size > maxAllowedSize) {
            infoMessage('your file exceed the maximum allowed file size');
            $('#certificate_pdf-preview').addClass('img-grayscale');
            return $(this).val('');
        }
    }
    $('#certificate_pdf-preview').removeClass('img-grayscale');
});

// select subject start
$('#achievement-subject_id').change(function() {
    if($(this).find(':selected').val() == '-') {
        $('#achievement-subject').html(`
            <input type="text" id="" name="subject" class="form-control form-control-sm mt-3" placeholder="Input the name of the subject" required>
        `);
    } else {
        $('#achievement-subject').html('');
    }
});
// select subject end

$(document).ready(function() {
    $('#link-achievement').addClass('active');
    $('#submenu-achievement').addClass('show');
    $('#link-achievement-create').addClass('active');
});
</script>
@endpush