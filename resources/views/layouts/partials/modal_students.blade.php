@push('css-styles')
<style>
.modal input { font-size: 9pt; }
.modal .form-label { color: var(--bs-primary); font-size: 10pt; }
.offcanvas p { font-size: .8em; margin-bottom: 0px; }
</style>
@endpush

<!-- modal password start -->
<div class="modal fade" id="modal-reset-password" aria-hidden="true"> 
    <div class="modal-dialog">
        <form action="action/admin" method="post" id="form-reset-password" class="m-0">
        <input type="hidden" name="action" value="reset_password">
        <input type="hidden" id="reset-password-user_id" name="user_id">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2"><i class='bx bx-key'></i><span>Reset password</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="fs-9 mb-3">Reset password of <span id="reset-password-email" class="text-primary"></span></p>
                <div class="input-group form-floating mb-3">
                    <input type="password" name="password" id="reset-password" class="input form-control form-control-sm" placeholder="Password" value="" required>
                    <span class="input-group-text hover-pointer" onclick="togglePassword(password_visible)">
                        <i class='btn-password-toggle bx bx-show-alt'></i>
                    </span>
                    <label for="reset-password" class="form-label">Password</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" id="reset-password_confirmation" class="form-control form-control-sm" name="password_confirmation" placeholder="Confirm Password">
                    <label for="reset-password_confirmation" class="form-label">Confirm password</label>
                </div>
                <p class="fs-8 fst-italic text-secondary mb-0">*) minimum 6 characters</p>
                <p id="alert-reset-password" class="alert alert-danger d-none mt-3"></p>
            </div>
            <div class="modal-footer gap-2">
                <button type="button" class="btn btn-sm btn-secondary gap-1" data-bs-dismiss="modal" aria-label="Close"><i class='bx bx-arrow-back'></i>Cancel</button>
                <button type="button" id="btn-password-submit" class="btn btn-sm btn-success gap-1" onclick="submitPassword()"><i class='bx bx-mail-send' ></i>Submit</button>
            </div>
        </div>
        </form>
    </div>
</div>
<!-- modal password end -->

<!-- modal sendNotification start -->
<div class="modal fade" id="modal-sendNotification" aria-hidden="true"> 
    <div class="modal-dialog">
        <form action="action/admin" method="post" id="form-sendNotification" class="m-0">
        <input type="hidden" name="action" value="send_notification">
        <input type="hidden" id="sendNotification-user_id" name="user_id">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2"><i class='bx bx-message'></i><span>Send notification</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="sendNotification-subject" class="form-label">Subject</label>
                    <input type="text" id="sendNotification-subject" name="subject" class="form-control form-control-sm">
                </div>
                <div class="mb-3">
                    <label for="sendNotification-message" class="form-label">Message</label>
                    <textarea name="message" id="sendNotification-message" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer gap-2">
                <button type="button" class="btn btn-sm btn-secondary gap-1" data-bs-dismiss="modal" aria-label="Close"><i class='bx bx-arrow-back'></i>Cancel</button>
                <button type="button" id="btn-sendNotification-submit" class="btn btn-sm btn-success gap-1" onclick="submitNotification()"><i class='bx bx-mail-send' ></i>Send</button>
            </div>
        </div>
        </form>
    </div>
</div>
<!-- modal sendNotification end -->

<!-- offcanvas studentProfile start -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="studentProfile" aria-labelledby="studentProfileLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="studentProfileLabel">Student profile</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="flex-center gap-3 mb-3">
            <img src="{{ asset('img/profiles/user.jpg') }}" alt="" class="border rounded" style="max-height:100px" id="studentProfile-picture">
            <div class="col">
                <p class="text-primary" id="studentProfile-email">-</p>
                <p class="fw-500 ls-1" id="studentProfile-full_name">-</p>
                <p id="studentProfile-grade">-</p>
            </div>
        </div>
        <table class="table fs-8">
            <tr>
                <td>Address</td>
                <td id="studentProfile-address">-</td>
            </tr>
            <tr>
                <td>Birth day</td>
                <td><span id="studentProfile-birth_place"></span>, <span id="studentProfile-birth_day"></span></td>
            </tr>
        </table>
    </div>
</div>
<!-- offcanvas studentProfile end -->

@push('scripts')
<script type="text/javascript">
// offcanvas Profile
const offcanvasProfile = (item_id) => {
    $('#studentProfile-picture').attr('src', $('#picture-'+item_id).html());
    $('#studentProfile-email').html($('#email-'+item_id).html());
    $('#studentProfile-full_name').html($('#full_name-'+item_id).html());
    $('#studentProfile-grade').html($('#grade-'+item_id).html());
    $('#studentProfile-birth_place').html($('#birth_place-'+item_id).val());
};

// sendNotification start
const modalNotification = (user_id) => {
    $('#sendNotification-user_id').val(user_id)
    $('#modal-sendNotification').modal('show');
};
const submitNotification = () => {
    let formData = new FormData($('#form-sendNotification')[0]);
    let config = {
        method: 'post', url: domain + 'action/admin', data: formData,
    }
    axios(config)
    .then((response) => {
        successMessage(response.data.message);
        $('.modal').modal('hide');
    })
    .catch((error) => {
        console.log(error);
        errorMessage(error.response.data.message);
    });
};

// reset password start
const submitPassword = () => {
    $('.alert').hide();
    let formData = new FormData($('#form-reset-password')[0]);
    let config = {
        method: 'post', url: domain + 'action/admin', data: formData,
    }
    axios(config)
    .then((response) => {
        successMessage(response.data.message);
        $('.modal').modal('hide');
        return location.reload();
    })
    .catch((error) => {
        console.log(error);
        errorMessage(error.response.data.message);
        $('#alert-reset-password').html('');
        error.response.data.messages.forEach(foreachResetPassword);
        function foreachResetPassword(item, index) {
            $('#alert-reset-password').append(item + '</br>');
        }
        $('#alert-reset-password').removeClass('d-none').hide().fadeIn('slow');
    });
};

const modalPassword = (user_id, email) => {
    $('#form-reset-password').trigger('reset');
    $('#reset-password-user_id').val(user_id);
    $('#reset-password-email').html(email);
    $('#modal-reset-password').modal('show');
};
// reset password end

var password_visible = false;
const togglePassword = () => {
    let input = $('[name="password"]');
    let input_confirm = $('[name="password_confirmation"]');
    let input_btn = $('.btn-password-toggle');
    if(password_visible == false) {
        input.attr("type", "text");
        input_confirm.attr("type", "text");
        input_btn.removeClass('bx-show-alt').addClass('bx-hide');
        password_visible = true;
    } else {
        input.attr("type", "password");
        input_confirm.attr("type", "password");
        input_btn.removeClass('bx-hide').addClass('bx-show-alt');
        password_visible = false;
    }
};

</script>
@endpush