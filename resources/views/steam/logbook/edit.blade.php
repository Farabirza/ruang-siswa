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
                    <li class="breadcrumb-item"><a href="/steamProject/{{$steamLogBook->steamProject->id}}">{{$steamLogBook->steamProject->title}}</a></li>
                    <li class="breadcrumb-item"><a href="/steamLogBook/{{$steamLogBook->id}}">{{$steamLogBook->title}}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
        <!-- breadcrumb end -->
    </div>

    <!-- container start -->
    <div class="container mb-3">
        <div class="row bg-white p-3 rounded shadow">
            <div class="col-md-12 py-2 mb-3">
                <h3 class="d-flex align-items-center gap-2 fs-16 m-0"><i class="bx bxs-file-plus"></i>Edit STEAM Log</h3>
            </div>
            <div class="col-md-12">
                <!-- form start -->
                <form action="logbook" method="post" id="form-steamLogBook" class="m-0" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="logbook-title" class="form-label">Title</label>
                    <input type="text" id="logbook-title" name="title" class="form-control" value="{{$steamLogBook->title}}">
                </div>
                <div class="mb-3">
                    <label for="logbook-content" class="form-label">Content</label>
                    <textarea name="content" id="logbook-content" class="form-control">{!! $steamLogBook->content !!}</textarea>
                </div>
                
                <!-- image handler start -->
                <div class="mb-3">
                    <label class="form-label">Documentation images</label>
                    <div id="container-documentation" class="flex-start flex-wrap gap-3">
                        <label for="steamLogBook-image">
                        <div class="flex-center rounded border btn-outline-primary" style="height:240px;width:240px;" role="button">
                            <div class="text-center" style="height:80px;width:60px;">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M432,112V96a48.14,48.14,0,0,0-48-48H64A48.14,48.14,0,0,0,16,96V352a48.14,48.14,0,0,0,48,48H80" style="fill:none;stroke:currentColor;stroke-linejoin:round;stroke-width:32px"/><rect x="96" y="128" width="400" height="336" rx="45.99" ry="45.99" style="fill:none;stroke:currentColor;stroke-linejoin:round;stroke-width:32px"/><ellipse cx="372.92" cy="219.64" rx="30.77" ry="30.55" style="fill:none;stroke:currentColor;stroke-miterlimit:10;stroke-width:32px"/><path d="M342.15,372.17,255,285.78a30.93,30.93,0,0,0-42.18-1.21L96,387.64" style="fill:none;stroke:currentColor;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px"/><path d="M265.23,464,383.82,346.27a31,31,0,0,1,41.46-1.87L496,402.91" style="fill:none;stroke:currentColor;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px"/></svg>
                            </div>
                        </div>
                        </label>
                        <?php $i = 1; ?>
                        @forelse($steamLogBook->image as $item)
                        <img id="steamLogBook-image-{{$i}}" src="{{asset('img/photos/'.$item->name)}}" class="img-thumbnail popper hover-pointer" style="height:240px;" data-caption="{{$item->caption}}" onclick="modalSteamLogBookImage(`{{$i}}`, '{{$item->id}}')">
                        <?php $i++; ?>
                        @empty
                        @endforelse
                    </div>
                    <div id="container-documentation-input" class="d-none">
                        <input type="file" name="image" id="steamLogBook-image" accept="image/*">
                    </div>
                </div>
                <div class="mb-3">
                    <p class="form-note">*) maximum allowed image size is 2 MB. You can go to <a href="https://www.iloveimg.com/resize-image" class="text-primary fw-500" target="_blank">this website</a> to resize your image's size</p>
                </div>
                <!-- image handler end -->

                <div class="mb-3 flex-end gap-3">
                    <hr class="col">
                    <button type="button" class="btn btn-sm btn-outline-secondary flex-start gap-2" onclick="clearContent()"><i class="bx bx-eraser"></i>Clear</button>
                    <button type="button" class="btn btn-sm btn-primary flex-start gap-2" onclick="submitLogBook()"><i class="bx bx-mail-send"></i>Submit</button>
                </div>
                </form>
                <!-- form end -->
            </div>
        </div>
    </div>
    <!-- container end -->
</section>

@endsection

@push('scripts')
<script src="{{ asset('/vendor/summernote/summernote-bs5.min.js') }}"></script>
<script type="text/javascript">

// image handler
var image_count = '{{count($steamLogBook->image)}}';
$('input[name="image"]').change(function(e) {
    if(image_count >= 10) {
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
        formData.append('action', 'uploadSteamLogImage');
        formData.append('image', e.target.result);
        let config = { method: 'Post', url: domain + 'action/steamLogBook', data: formData };
        axios(config)
        .then((response) => {
            successMessage(response.data.message);
            image_count += 1;
            $('#container-documentation').append(`
                <img id="logbook-image-`+ image_count +`" class="img-thumbnail popper hover-pointer" style="height:240px;" data-caption="" onclick="modalAchievementImage(`+ image_count +`, '`+ response.data.image_id +`')">
            `);
            $('#logbook-image-' + image_count).attr('src', e.target.result); 
            $('#documentation-input').append(
                '<input type="text" id="image-'+ image_count +'" name="images['+ image_count +']" value="'+ e.target.result +'">'
            );
        })
        .catch((error) => {
            console.log(error.response.data);
            errorMessage(error.response.data.message);
        });
    }
    reader.readAsDataURL(this.files[0]); 
});

// form handler
const submitLogBook = () => {
    let formData = new FormData($('#form-steamLogBook')[0]);
    formData.append('_method', 'put');
    let config = {
        method: 'post', url: domain + 'steamLogBook/{{$steamLogBook->id}}', data: formData,
    };
    axios(config)
    .then((response) => {
        successMessage(response.data.message);
        window.location.href = '/steamProject/{{$steamLogBook->steamProject->id}}';
    })
    .catch((error) => {
        errorMessage(error.response.data.message);
        console.log(error.response.data);
    });
};
const clearContent = () => {
    $('#logbook-content').summernote('code', '')
};

$(document).ready(function() {
  $('#logbook-content').summernote({
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