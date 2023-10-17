@extends('layouts.master')

@push('css-styles')
<link href="{{ asset('/vendor/summernote/summernote-bs5.min.css') }}" rel="stylesheet">
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
                    <li class="breadcrumb-item"><a href="/steamProject">STEAM project</a></li>
                    <li class="breadcrumb-item active" aria-current="page">New project</li>
                </ol>
            </nav>
        </div>
        <!-- breadcrumb end -->
    </div>

    <!-- container start -->
    <div class="container mb-3">
        <div class="row bg-white p-3 rounded shadow">
            <div class="col-md-12 py-2 mb-3">
                <h3 class="d-flex align-items-center gap-2 fs-16 m-0"><i class="bx bx-plus-circle"></i>New Project</h3>
            </div>
            <div class="col-md-12">
                <form action="steamProject" method="post" id="form-steam" class="m-0" enctype="multipart/form-data">
                <div class="mb-3 bg-light p-4 border">
                    <p class="text-center m-0"><label class="form-label">Cover image</label></p>
                    <div class="flex-center mb-3">
                        <img src="{{asset('img/materials/noimage.jpg')}}" id="steam-image-preview" class="rounded border" style="height:320px">
                    </div>
                    <p class="text-center m-0">
                        <label for="steam-image" class="border border-1 border-primary btn-outline-primary btn-sm py-2 px-4 rounded-pill" role="button"><span class="flex-start gap-2"><i class="bx bx-upload"></i>Choose image</span></label>
                    </p>
                    <div class="d-none">
                        <input type="file" name="image" id="steam-image" class="form-control form-control-sm" accept="image/*">
                        <input type="hidden" name="image_base64" id="steam-image-base64">
                    </div>
                </div>
                <div class="mb-3">
                    <p class="form-note">*) maximum allowed image size is 2 MB. You can go to <a href="https://www.iloveimg.com/resize-image" class="text-primary fw-500" target="_blank">this website</a> to resize your image's size.</p>
                </div>
                <div class="mb-3 d-flex flex-align-center flex-remove-md gap-3">
                    <div class="col">
                        <label for="steam-title" class="form-label">Project title</label>
                        <input type="text" id="steam-title" name="title" class="form-control form-control-sm" required>
                        <p class="form-note">*) required</p>
                        <p id="alert-title" class="alert alert-danger d-none"></p>
                    </div>
                    <div class="col">
                        <label for="steam-category" class="form-label">Project category</label>
                        <select name="steamCategory_id" id="steam-category" class="form-select form-select-sm" required>
                            @forelse($steamCategories as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                            @empty
                            <option value="">Select category</option>
                            @endforelse
                        </select>
                        <p class="form-note">*) required</p>
                        <p id="alert-steamCategory_id" class="alert alert-danger d-none"></p>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="steam-description" class="form-label">Description</label>
                    <textarea name="description" id="steam-description" style="min-height:120px" class="form-control form-control-sm"></textarea>
                    <p id="alert-description" class="alert alert-danger d-none"></p>
                </div>
                <div class="mb-3 flex-end gap-3">
                    <hr class="col">
                    <button type="submit" class="btn btn-sm btn-primary flex-start gap-2"><i class="bx bx-mail-send"></i>Submit</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- container end -->
</section>

@endsection

@push('scripts')
<script src="{{ asset('/vendor/summernote/summernote-bs5.min.js') }}"></script>
<script type="text/javascript">

// form handler
$('#form-steam').submit(function (e) {
    e.preventDefault();
    let formData = new FormData($(this)[0]);
    let config = {
        method: $(this).attr('method'), url: domain + $(this).attr('action'), data: formData,
    };
    axios(config)
    .then((response) => {
        successMessage(response.data.message);
        window.location.href = '/steamProject/'+response.data.steam_id;
    })
    .catch((error) => {
      console.log(error.response);
      errorMessage(error.response.data.message);
      if(error.response.data) {
          validationMessage(error.response.data.errors);
      }
    })
});

// file handler
$('input[name="image"]').change(function(e) {
    if(e.target.files && e.target.files[0]) {
        let file = e.target.files[0];
        const maxAllowedSize = 2 * 1024 * 1024;
        if(file.size > maxAllowedSize) {
            infoMessage('your file exceed the maximum allowed file size');
            return $(this).val('');
        }
    }
    let reader = new FileReader();
    reader.onload = (e) => { 
        $('#steam-image-preview').attr('src', e.target.result);
        $('#steam-image-base64').val(e.target.result);
    }
    reader.readAsDataURL(this.files[0]); 
});


$(document).ready(function() {
  $('#steam-description').summernote({
    height: 400,
    toolbar: [
        [ 'style', [ 'style' ] ],
        [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
        [ 'fontname', [ 'fontname' ] ],
        [ 'fontsize', [ 'fontsize' ] ],
        [ 'color', [ 'color' ] ],
        [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
        [ 'table', [ 'table' ] ],
        [ 'insert', [ 'link'] ],
        [ 'view', [ 'undo', 'redo', 'fullscreen', 'codeview', 'help' ] ]
    ]
  });
});
</script>
@endpush