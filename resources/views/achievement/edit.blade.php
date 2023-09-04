@extends('layouts.master')

@push('css-styles')
<link href="{{ asset('/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
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
                    <li class="breadcrumb-item"><a href="/achievement/{{$achievement->id}}">{{$achievement->title}}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
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
                <p class="alert alert-success mb-3">{{ session('success') }}</p>
                @endif
                <p id="alert-success" class="alert alert-success mb-3 d-none"></p>
                <h3 class="d-flex align-items-center gap-2 fs-16 mb-3"><i class="bx bx-edit-alt"></i>Edit achievement data</h3>

                <!-- form achievement start -->
                <form action="achievement/{{$achievement->id}}" method="Put" id="form-achievement-edit" class="formHandler m-0">
                <div class="mb-3">
                    <label for="achievement-user_id" class="form-label">Student</label>
                    <input type="text" class="form-control form-control-sm" value="{{$user->profile->full_name}} | {{$user->email}}" disabled>
                    <input type="hidden" name="user_id" value="{{$user->id}}">
                </div>
                <div class="mb-3 d-flex flex-remove-md gap-3">
                    <div class="col">
                        <label for="achievement-title" class="form-label">Title</label>
                        <input type="text" name="title" id="achievement-title" class="form-control form-control-sm" placeholder="ex: Juara 1 Olimpiade Sains Nasional" value="{{$achievement->title}}" required>
                        <p class="form-note">*) required</p>
                        <p id="alert-title" class="alert alert-danger d-none"></p>
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
                        <input type="number" name="year" id="achievement-year" class="form-control form-control-sm" value="{{$achievement->year}}" placeholder="ex: {{date('Y')}}">
                        <p id="alert-year" class="alert alert-danger d-none"></p>
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
                    <p id="alert-subject" class="alert alert-danger d-none"></p>
                </div>
                <div class="mb-3 d-flex flex-remove-md gap-3">
                    <div class="col">
                        <label for="achievement-organizer" class="form-label">Organizer</label>
                        <input type="text" name="organizer" id="achievement-organizer" class="form-control form-control-sm" placeholder="ex: Kementerian Pendidikan RI" value="{{$achievement->organizer}}">
                        <p id="alert-organizer" class="alert alert-danger d-none"></p>
                    </div>
                    <div class="col">
                        <label for="achievement-url" class="form-label">URL</label>
                        <input type="text" name="url" id="achievement-url" class="form-control form-control-sm" placeholder="ex: https://pribadidepok.sch.id" value="{{$achievement->url}}">
                        <p class="form-note">*) link towards competition or organizer's homepage</p>
                        <p id="alert-url" class="alert alert-danger d-none"></p>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="achievement-description" class="form-label">Description</label>
                    <textarea name="description" id="achievement-description" style="min-height:80px" class="form-control form-control-sm" placeholder="Additional information">{{$achievement->description}}</textarea>
                    <p id="alert-description" class="alert alert-danger d-none"></p>
                </div>
                <div class="flex-end gap-3 my-4">
                    <hr class="col">
                    <button type="submit" class="btn btn-success flex-center gap-2"><i class="bx bx-edit-alt"></i>Update</button>
                </div>
                </form>
                <!-- form achievement end -->
                <div class="mb-3 d-flex flex-remove-md gap-3">
                    <div class="col">
                        <!-- form certificate image start -->
                        <form action="action/achievement" method="post" class="formHandler" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="uploadCertificateImage">
                        <input type="hidden" name="achievement_id" value="{{$achievement->id}}">
                        <p class="m-0"><label for="achievement-certificate_image" class="form-label">Certificate image</label></p>
                        @if($achievement->certificate_image)
                        <a href="{{asset('img/certificate/'.$achievement->certificate_image)}}" class="glightbox">
                            <img src="{{asset('img/certificate/'.$achievement->certificate_image)}}" id="certificate_image-preview" class="img-thumbnail mb-3" style="max-height:240px">
                        </a>
                        @else
                        <img src="{{asset('img/materials/noimage.jpg')}}" id="certificate_image-preview" class="img-thumbnail mb-3" style="max-height:240px">
                        @endif
                        <div class="mb-3">
                            <input type="file" name="certificate_image" id="achievement-certificate_image" class="form-control form-control-sm" accept="image/*">
                            <input type="hidden" name="certificate_image_base64" id="base64-certificate_image">
                        </div>
                        <div class="flex-end gap-2">
                            <a href="/achievement/{{$achievement->id}}/remove/image" class="btn btn-outline-danger btn-sm flex-start gap-2 mb-2 btn-warn" data-warning="Do you wish to remove this image file?"><i class="bx bx-trash-alt"></i>Remove</a>
                            <button type="submit" class="btn btn-primary btn-sm flex-start gap-2 mb-2"><i class="bx bx-upload"></i>Upload</button>
                        </div>
                        </form>
                        <!-- form certificate image end -->
                    </div>
                    <div class="col">
                        <!-- form certificate pdf start -->
                        <form action="/action/achievement" method="post" enctype="multipart/form-data" id="form-certificate_pdf">
                        @csrf
                        <input type="hidden" name="action" value="uploadCertificatePdf">
                        <input type="hidden" name="achievement_id" value="{{$achievement->id}}">
                        <p class="m-0"><label for="achievement-certificate_pdf" class="form-label">Certificate PDF</label></p>
                        @if($achievement->certificate_pdf)
                        <a href="{{asset('img/certificate/pdf/'.$achievement->certificate_pdf)}}" target="_blank">
                            <img src="{{asset('img/materials/pdf.jpg')}}" id="certificate_pdf-preview" class="img-thumbnail mb-3" style="max-height:240px">
                        </a>
                        @else
                        <img src="{{asset('img/materials/pdf.jpg')}}" id="certificate_pdf-preview" class="img-thumbnail img-grayscale mb-3" style="max-height:240px">
                        @endif
                        <div class="mb-3">
                            <input type="file" name="certificate_pdf" id="achievement-certificate_pdf" class="form-control form-control-sm" accept=".pdf" required>
                        </div>
                        <div class="flex-end gap-2">
                            <a href="/achievement/{{$achievement->id}}/remove/pdf" class="btn btn-outline-danger btn-sm flex-start gap-2 mb-2 btn-warn" data-warning="Do you wish to remove this PDF file?"><i class="bx bx-trash-alt"></i>Remove</a>
                            <button type="submit" class="btn btn-primary btn-sm flex-start gap-2 mb-2"><i class="bx bx-upload"></i>Upload</button>
                        </div>
                        </form>
                        <!-- form certificate pdf end -->
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Documentation images</label>
                    <div id="container-documentation" class="flex-start flex-wrap gap-3">
                        <label for="achievement-image">
                        <div class="flex-center rounded border btn-outline-primary" style="height:240px;width:240px;" role="button">
                            <div class="text-center" style="height:80px;width:60px;">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M432,112V96a48.14,48.14,0,0,0-48-48H64A48.14,48.14,0,0,0,16,96V352a48.14,48.14,0,0,0,48,48H80" style="fill:none;stroke:currentColor;stroke-linejoin:round;stroke-width:32px"/><rect x="96" y="128" width="400" height="336" rx="45.99" ry="45.99" style="fill:none;stroke:currentColor;stroke-linejoin:round;stroke-width:32px"/><ellipse cx="372.92" cy="219.64" rx="30.77" ry="30.55" style="fill:none;stroke:currentColor;stroke-miterlimit:10;stroke-width:32px"/><path d="M342.15,372.17,255,285.78a30.93,30.93,0,0,0-42.18-1.21L96,387.64" style="fill:none;stroke:currentColor;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px"/><path d="M265.23,464,383.82,346.27a31,31,0,0,1,41.46-1.87L496,402.91" style="fill:none;stroke:currentColor;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px"/></svg>
                            </div>
                        </div>
                        </label>
                        <!-- achievement images start -->
                        <?php $i = 1; ?>
                        @forelse($achievement->image as $item)
                        <img id="achievement-image-{{$i}}" src="{{asset('img/photos/'.$item->file_name)}}" class="img-thumbnail popper hover-pointer" style="height:240px;" data-caption="{{$item->caption}}" onclick="modalAchievementImage(`{{$i}}`, '{{$item->id}}')">
                        <?php $i++; ?>
                        @empty
                        @endforelse
                        <!-- achievement images end -->
                    </div>
                    <div id="container-documentation-input" class="d-none">
                        <input type="file" name="image" id="achievement-image" accept="image/*">
                    </div>
                </div>
                <div class="mb-3">
                    <p class="form-note">*) maximum allowed image size is 2 MB. You can go to <a href="https://www.iloveimg.com/resize-image" class="text-primary fw-500" target="_blank">this website</a> to resize your image's size</p>
                </div>

            </div>
        </div>
    </div>
</section>


<!-- modal achievement-image start -->
<div class="modal fade" id="modal-achievementImage" aria-hidden="true"> 
    <div class="modal-dialog">
        <form action="action/achievement" method="post" id="form-achievement-image" class="formHandler m-0">
        <input type="hidden" name="action" value="updateAchievementImage">
        <input type="hidden" name="image_id" id="achievementImage-image_id" value="">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2"><i class='bx bx-image-alt'></i><span>Documentation image</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div class="flex-center mb-3">
                <img id="achievement-image-preview" src="" class="img-thumbnail" style="max-height:320px;">
            </div>
            <label for="achievement-image-caption" class="form-label">Caption</label>
            <input type="text" id="achievement-image-caption" name="caption" class="form-control form-control-sm">
            </div>
            <div class="modal-footer gap-1">
                <a href="/image/{image_id}/delete" id="btn-achievementImage-delete" class="btn btn-outline-danger btn-sm flex-start gap-1 btn-warn" data-warning="Do you wish to delete this image?"><i class="bx bx-trash-alt"></i>Delete</a>
                <button type="submit" id="btn-achievementImage-submit" class="btn btn-sm btn-success gap-1"><i class='bx bx-edit-alt' ></i>Update</button>
            </div>
        </div>
        </form>
    </div>
</div>
<!-- modal achievement-image end -->

@endsection

@push('scripts')
<script src="{{ asset('/vendor/glightbox/js/glightbox.min.js') }}"></script>
<script type="text/javascript">
$('#achievement-level option[value="'+'{{$achievement->level}}'+'"]').prop('selected', true);
$('#achievement-grade_level option[value="'+'{{$achievement->grade_level}}'+'"]').prop('selected', true);
$('#achievement-subject_id option[value="'+'{{$achievement->subject_id}}'+'"]').prop('selected', true);

function modalAchievementImage (item_id, image_id) {
    let image_caption = ($('#achievement-image-'+item_id).attr('data-caption')) ? $('#achievement-image-'+item_id).attr('data-caption') : '';
    $('#achievement-image-caption').val(image_caption);
    $('#achievement-image-preview').attr('src', $('#achievement-image-'+item_id).attr('src'));
    $('#achievementImage-image_id').val(image_id);
    $('#btn-achievementImage-delete').attr('href', '/image/'+ image_id +'/delete');
    $('#modal-achievementImage').modal('show');
}

// file handler
var image_number = 1;
$('input[name="image"]').change(function(e) {
    if(image_number >= 10) {
        return infoMessage("you've reached the maximum allowed number of files");
    }
    if(e.target.files && e.target.files[0]) {
        let file = e.target.files[0];
        const maxAllowedSize = 2 * 1024 * 1024;
        if(file.size > maxAllowedSize) {
            return infoMessage('your file exceed the maximum allowed file size');
        }
    }
    let image_val = $(this)[0].files[0];
    let reader = new FileReader();
    reader.onload = (e) => { 
        let formData = new FormData();
        formData.append('action', 'uploadAchievementImage');
        formData.append('user_id', '{{$user->id}}');
        formData.append('achievement_id', '{{$achievement->id}}');
        formData.append('achievement_image', e.target.result);
        let config = { method: 'Post', url: domain + 'action/achievement', data: formData };
        axios(config)
        .then((response) => {
            successMessage(response.data.message);
            image_number += 1;
            $('#container-documentation').append(`
                <img id="achievement-image-`+ image_number +`" class="img-thumbnail popper hover-pointer" style="height:240px;" data-caption="" onclick="modalAchievementImage(`+ image_number +`, '`+ response.data.image_id +`')">
            `);
            $('#achievement-image-' + image_number).attr('src', e.target.result); 
            $('#documentation-input').append(
                '<input type="text" id="image-'+ image_number +'" name="images['+ image_number +']" value="'+ e.target.result +'">'
            );
        })
        .catch((error) => {
            console.log(error.response.data);
            errorMessage(error.response.data.message);
        });
    }
    reader.readAsDataURL(this.files[0]); 
});
$('input[name="certificate_image"]').change(function(e) {
    if(e.target.files && e.target.files[0]) {
        let file = e.target.files[0];
        let maxAllowedSize = 1 * 1024 * 1024;
        if(file.size > maxAllowedSize) {
            infoMessage('your file exceed the maximum allowed file size');
            $('#base64-certificate_image').val('');
            return $(this).val('');
        }
    }
    let reader = new FileReader();
    reader.onload = (e) => { 
        $('#certificate_image-preview').attr('src', e.target.result);
        $('#base64-certificate_image').val(e.target.result);
    }
    reader.readAsDataURL(this.files[0]); 
});
$('input[name="certificate_pdf"]').change(function(e) {
    if(e.target.files && e.target.files[0]) {
        let file = e.target.files[0];
        let maxAllowedSize = 1 * 1024 * 1024;
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

// glightbox
const lightbox = GLightbox({
    touchNavigation: true,
    loop: true,
    autoplayVideos: true
});

$(document).ready(function() {
    $('#link-achievement').addClass('active');
    $('#submenu-achievement').addClass('show');
});
</script>
@endpush