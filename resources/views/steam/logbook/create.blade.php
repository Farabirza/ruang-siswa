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
                    <li class="breadcrumb-item"><a href="/steamProject/{{$steamProject->id}}">{{$steamProject->title}}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Log book</li>
                </ol>
            </nav>
        </div>
        <!-- breadcrumb end -->
    </div>

    <!-- container start -->
    <div class="container mb-3">
        <div class="row bg-white p-3 rounded shadow">
            <div class="col-md-12 py-2 mb-3">
                <h3 class="d-flex align-items-center gap-2 fs-16 m-0"><i class="bx bxs-file-plus"></i>New STEAM Log</h3>
            </div>
            <div class="col-md-12">
                <!-- form start -->
                <form action="logbook" method="post" id="form-steamLogBook" class="m-0" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="logbook-title" class="form-label">Title</label>
                    <input type="text" id="logbook-title" name="title" class="form-control" value="Update for {{date('l, j F Y', time())}}">
                </div>
                <div class="mb-3">
                    <label for="logbook-content" class="form-label">Content</label>
                    <textarea name="content" id="logbook-content" class="form-control"></textarea>
                </div>
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

// form handler
const submitLogBook = () => {
    let formData = new FormData($('#form-steamLogBook')[0]);
    formData.append('action', 'new_log');
    formData.append('user_id', '{{Auth::user()->id}}');
    formData.append('steamProject_id', '{{$steamProject->id}}');
    let config = {
        method: 'post', url: domain + 'action/logbook', data: formData,
    };
    axios(config)
    .then((response) => {
        successMessage(response.data.message);
        window.location.href = '/steamProject/{{$steamProject->id}}';
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