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
                @if(session('success'))
                <p class="alert alert-success mb-3">Success! New achievement data has been stored</p>
                @endif
                <h3 class="d-flex align-items-center gap-2 fs-16 mb-3"><i class="bx bx-plus-circle"></i>New achievement</h3>

                <!-- form achievement start -->
                <form action="/achievement" method="post" id="form-achievement" class="m-0" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="achievement-user_id" class="form-label">Student</label>
                    @if(Auth::user()->profile->role == 'student')
                    <select name="user_id" id="achievement-user_id" class="form-select form-select-sm" disabled>
                    @else
                    <select name="user_id" id="achievement-user_id" class="form-select form-select-sm">
                    @endif
                        @forelse($students as $item)
                        @if(Auth::user()->id == $item->id)
                        <option value="{{$item->id}}" selected>{{$item->profile->full_name}} | {{$item->email}}</option>
                        @else
                        <option value="{{$item->id}}">{{$item->profile->full_name}} | {{$item->email}}</option>
                        @endif
                        @empty
                        <option value="">Empty</option>
                        @endforelse
                    </select>
                </div>
                <div class="mb-3 d-flex flex-remove-md gap-3">
                    <div class="col">
                        <label for="achievement-title" class="form-label">Title</label>
                        <input type="text" name="title" id="achievement-title" class="form-control form-control-sm" placeholder="ex: Juara 1 Olimpiade Sains Nasional" value="{{ old('title') }}" autocomplete="off" required>
                        <p class="form-note">*) required</p>
                    </div>
                    <div class="col">
                        <label for="achievement-level" class="form-label">Regional level</label>
                        <select name="level" id="achievement-level" class="form-select form-select-sm">
                            <option value="local">Local</option>
                            <option value="national">National</option>
                            <option value="international">International</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3 d-flex flex-remove-md gap-3">
                    <div class="col">
                        <label for="achievement-grade_level" class="form-label">Grade level</label>
                        <select name="grade_level" id="achievement-grade_level" class="form-select form-select-sm">
                            <optgroup>
                                <option value="12 senior high">3rd grade senior high school</option>
                                <option value="11 senior high">2nd grade senior high school</option>
                                <option value="10 senior high">1st grade senior high school</option>
                            </optgroup>
                            <optgroup>
                                <option value="9 junior high">3rd grade junior high school</option>
                                <option value="8 junior high">2nd grade junior high school</option>
                                <option value="7 junior high">1st grade junior high school</option>
                            </optgroup>
                            <optgroup>
                                <option value="6 elementary">6th grade elementary school</option>
                                <option value="5 elementary">5th grade elementary school</option>
                                <option value="4 elementary">4th grade elementary school</option>
                                <option value="3 elementary">3rd grade elementary school</option>
                                <option value="2 elementary">2nd grade elementary school</option>
                                <option value="1 elementary">1st grade elementary school</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="col">
                        <label for="achievement-year" class="form-label">Year</label>
                        <input type="number" name="year" id="achievement-year" class="form-control form-control-sm" value="{{ old('year') }}" placeholder="ex: {{date('Y')}}">
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
                        <label for="achievement-certificate_image" class="form-label">Certificate image</label>
                        <input type="file" name="certificate_image" id="achievement-certificate_image" class="form-control form-control-sm" accept="image/*" value="{{ old('certificate_image') }}">
                    </div>
                    <div class="col">
                        <label for="achievement-certificate_pdf" class="form-label">Certificate PDF</label>
                        <input type="file" name="certificate_pdf" id="achievement-certificate_pdf" class="form-control form-control-sm" accept=".pdf" value="{{ old('certificate_pdf') }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Documentation images</label>
                    <div id="container-documentations" class="flex-start flex-wrap gap-3">
                        <label for="achievement-documentation">
                        <div class="flex-center rounded border btn-outline-primary" style="height:240px;width:240px;" role="button">
                            <div class="text-center" style="height:80px;width:60px;">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M432,112V96a48.14,48.14,0,0,0-48-48H64A48.14,48.14,0,0,0,16,96V352a48.14,48.14,0,0,0,48,48H80" style="fill:none;stroke:currentColor;stroke-linejoin:round;stroke-width:32px"/><rect x="96" y="128" width="400" height="336" rx="45.99" ry="45.99" style="fill:none;stroke:currentColor;stroke-linejoin:round;stroke-width:32px"/><ellipse cx="372.92" cy="219.64" rx="30.77" ry="30.55" style="fill:none;stroke:currentColor;stroke-miterlimit:10;stroke-width:32px"/><path d="M342.15,372.17,255,285.78a30.93,30.93,0,0,0-42.18-1.21L96,387.64" style="fill:none;stroke:currentColor;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px"/><path d="M265.23,464,383.82,346.27a31,31,0,0,1,41.46-1.87L496,402.91" style="fill:none;stroke:currentColor;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px"/></svg>
                            </div>
                        </div>
                        </label>
                    </div>
                    <p class="form-note">*) maximum allowed file size is 2 MB. You can go to <a href="https://www.iloveimg.com/resize-image" class="text-primary fw-500" target="_blank">this website</a> to resize your image's size</p>
                    <div id="documentation-input" class="d-none">
                        <input type="file" name="image" id="achievement-documentation">
                    </div>
                </div>
                <div class="flex-end gap-3">
                    <button class="btn btn-primary flex-center gap-2"><i class="bx bx-mail-send"></i>Submit</button>
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
    
// documentation
var image_number = 1;
$('input[name="image"]').change(function(e) {
    if(image_number >= 4) {
        return infoMessage("you've reached the maximum allowed number of files");
    }
    if(e.target.files && e.target.files[0]) {
        let file = e.target.files[0];
        const maxAllowedSize = 1 * 1024 * 1024;
        if(file.size > maxAllowedSize) {
            return infoMessage('your file exceed the maximum allowed file size');
        }
    }
    let image_val = $(this)[0].files[0];
    let reader = new FileReader();
    reader.onload = (e) => { 
        image_number += 1;
        $('#container-documentations').append(
            '<img id="documentation-image-'+ image_number +'" src="" class="img-thumbnail popper hover-pointer" style="height:240px;" title="" onclick="modalImage('+ image_number +')">'
        );
        $('#documentation-image-' + image_number).attr('src', e.target.result); 
        $('#documentation-input').append(
            '<input type="text" id="image-'+ image_number +'" name="images['+ image_number +']" value="'+ e.target.result +'">'
        );
    }
    reader.readAsDataURL(this.files[0]); 
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