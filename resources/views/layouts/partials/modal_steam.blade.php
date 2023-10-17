@push('css-styles')
<style>
.modal input { font-size: 9pt; }
.modal .form-label { color: var(--bs-primary); font-size: 10pt; }
</style>
@endpush

<!-- modal student start -->
<div class="modal fade" id="modal-steamMember" aria-hidden="true"> 
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2"><i class='bx bx-user'></i><span>Project member</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <img src="{{asset('img/profiles/user.jpg')}}" id="steamMember-picture" class="rounded-circle shadow mb-3" style="height: 240px;">
                    <p id="steamMember-name" class="m-0">User Name</p>
                </div>
            </div>
            <div class="modal-footer gap-2">
                @if(Auth::check() && (Auth::user()->profile->role != 'student' || $isMember == true))
                <button type="button" id="steamMember-btn-remove" class="btn btn-sm btn-outline-danger gap-1" onclick="removeSteamMember()"><i class='bx bx-user-minus'></i>Remove</button>
                @endif
                <a href="" id="steamMember-profile" class="btn btn-sm btn-success gap-1"><i class='bx bx-user'></i>Profile</a>
            </div>
        </div>
    </div>
</div>
<!-- modal student end -->

<!-- offcanvas steam member start -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvas-steamMember" aria-labelledby="label-steamMember">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title flex-start gap-2" id="label-steamMember"><i class="bx bx-user-plus"></i>Add STEAM project member</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="mb-3">
            <input type="text" name="student_keywords" class="form-control" id="steamMember-input" placeholder="Insert user name or email">
        </div>
        <ul id="container-steamMembers" class="list-group">
            <li class="list-group-item text-muted">Type something</li>
        </ul>
    </div>
</div>
<!-- offcanvas steam member end -->

@push('scripts')
<script type="text/javascript">
var typingTimer;
const steam_id = '{{$steam->id}}';
const doneTypingInterval = 500;
const $input = $('#steamMember-input');

const showSteamMember = (item_id) => {
    var $steamMember = $('#steamMember-'+item_id);
    let user_id = $steamMember.data('user_id');
    let profile_id = $steamMember.data('profile_id')
    $('#steamMember-picture').attr('src', $steamMember.attr('src'));
    $('#steamMember-name').html($steamMember.data('title'));
    $('#steamMember-btn-remove').attr('onclick', 'removeSteamMember('+item_id+', "'+user_id+'")');
    $('#steamMember-profile').attr('href', '/profile/'+profile_id);
    $('#modal-steamMember').modal('show');
};

const removeSteamMember = (item_id, user_id) => {
    let formData = { action: 'remove_member', user_id: user_id, steam_id: steam_id };
    let config = { method: 'post', url: domain+'action/steam', data: formData };
    axios(config)
    .then((response) => {
        infoMessage(response.data.message);
        $('#steamMember-item-'+item_id).remove();
        $('.modal').modal('hide');
    })
    .catch((error) => {
      console.log(error.response);
      errorMessage(error.response.data.message);
    });
};

$input.on('keyup', function () {
  clearTimeout(typingTimer);
  if($input.val() != '') {
    typingTimer = setTimeout(doneTyping, doneTypingInterval);
  } else {
    $('#container-steamMembers').html(`<li class="list-group-item text-muted">Type something</li>`);
  }
});

$input.on('keydown', function () {
  clearTimeout(typingTimer);
});

function doneTyping () {
    $('#container-steamMembers').html(`<div class="spinner"></div>`);
    let formData = { action: 'get_students', student_keywords: $input.val() };
    let config = { method:  'post', url: domain + 'action/student', data: formData, };
    axios(config)
    .then((response) => {
        $('#container-steamMembers').html(``);
        if(response.data.students.length > 0) {
            response.data.students.forEach(item => {
                $('#container-steamMembers').append(`<li class="list-group-item flex-between"><span>`+item.profile.full_name+`</span><i class="bx bx-user-plus p-1 border rounded-circle btn-outline-primary" role="button" onclick="addSteamMember('`+item.id+`')"></i></li>`)
            });
        } else {
            $('#container-steamMembers').append(`<li class="list-group-item">Not found</li>`)
        }
    })
    .catch((error) => {
      console.log(error.response);
    });
}

const addSteamMember = (user_id) => {
    let formData = { action: 'add_member', steam_id: steam_id, user_id: user_id };
    let config = { method: 'post', url: domain+'action/steam', data: formData };
    axios(config)
    .then((response) => {
        successMessage(response.data.message);
        fetchSteamMembers();
    })
    .catch((error) => {
      console.log(error.response);
      infoMessage(error.response.data.message);
    });
};

const fetchSteamMembers = () => {
    let formData = { action: 'fetch_members', steam_id: steam_id };
    let config = { method: 'post', url: domain+'action/steam', data: formData };
    axios(config)
    .then((response) => {
        $('#container-steamMember-items').html('');
        response.data.students.forEach(function (item, index) {
            let user_picture = (item.picture) ? domain+'img/profiles/'+item.picture : domain+'img/profiles/user.jpg';
            $('#container-steamMember-items').append(`
                <div id="steamMember-item-`+index+`" class="text-center">
                    <img src="`+user_picture+`" id="steamMember-`+index+`" class="rounded-circle border hover-grow mb-2 popper" style="height:80px" data-title="`+item.profile.full_name+`" data-user_id="`+item.id+`" data-profile_id="`+item.profile.id+`" role="button" onclick="showSteamMember('`+index+`')">
                    <p class="fs-8 m-0 text-wrap" style="max-width:100px">`+item.profile.full_name+`</p>
                </div>
            `);
        });
    })
    .catch((error) => {
      console.log(error.response);
    });
};

const modalSteamMember = () => {
    $('#modal-steam-member').modal('show');
};
</script>
@endpush