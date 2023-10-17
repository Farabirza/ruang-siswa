@push('css-styles')
<style>
.modal .form-label { color: var(--bs-primary); font-size: .9em; }
.modal .form-note { color: var(--bs-secondary); font-style: italic; font-size: .8em; margin-top: 8px; margin-bottom: 0; }
.modal .alert { font-size: .9em; padding: 10px; margin-top: 10px; margin-bottom: 0; }
</style>
@endpush

<!-- modal student start -->
<div class="modal fade" id="modal-steamCategory" aria-hidden="true"> 
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2"><i class='bx bx-analyse'></i><span>Project category</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- form start -->
            <form action="/steamCategory" method="post" id="form-steamCategory" class="m-0" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="steamCategory-method" value="post">
            <div class="modal-body">
                <div class="text-center mb-4">
                    <img src="{{asset('img/materials/noimage.jpg')}}" id="steamCategory-image-preview" class="rounded shadow" style="height: 240px;">
                </div>
                <input type="file" name="image" id="steamCategory-image" class="form-control mb-3">
                <div class="mb-3">
                    <p class="form-note">*) maximum allowed image size is 2 MB. You can go to <a href="https://www.iloveimg.com/resize-image" class="text-primary fw-500" target="_blank">this website</a> to resize your image's size.</p>
                </div>
                <div class="mb-3">
                    <label for="steamCategory-name" class="form-label">Name</label>
                    <input type="text" name="name" id="steamCategory-name" class="form-control">
                </div>
                <div class="mb-0">
                    <label for="steamCategory-description" class="form-label">Description</label>
                    <textarea name="description" id="steamCategory-description" style="min-height:120px" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-start gap-2"><i class="bx bx-plus"></i>Submit</button>
            </div>
            </form>
            <!-- form end -->
        </div>
    </div>
</div>
<!-- modal student end -->

@push('scripts')
<script type="text/javascript">

const modalSteamCategory = (method, item_id) => {
    if(method == 'post') {
        $('#form-steamCategory').trigger('reset').attr('action', '/steamCategory');
        $('#steamCategory-method').val('post');
    } else {
        category_id = $('input#steamCategory_id-' + item_id).val();
        $('#steamCategory-method').val('put');
        $('#form-steamCategory').attr('action', '/steamCategory/'+category_id);
        $('#steamCategory-image-preview').attr('src', $('#steamCategory-image-' + item_id).attr('src'));
        $('#steamCategory-name').val($('#steamCategory-name-' + item_id).html());
        $('#steamCategory-description').val($('#steamCategory-description-' + item_id).html());
    }
    $('#modal-steamCategory').modal('show');
};

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
        $('#steamCategory-image-preview').attr('src', e.target.result);
    }
    reader.readAsDataURL(this.files[0]); 
});
</script>
@endpush