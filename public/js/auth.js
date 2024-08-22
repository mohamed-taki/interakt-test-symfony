$(document).ready(function () {
    // automatically add an eye toggler for password togglers
    $(".password-toggler-container").append(`<i class="fa fa-eye position-absolute" onclick="togglePasswordVisibility(this)"></i>`)

    // if(!$(".password-toggler-container").hasClass('position-relative')){
    //     $(".password-toggler-container").addClass('position-relative')
    // }
});

function togglePasswordVisibility(elem){
    if($(elem).prev().attr('type') === 'text') {
        $(elem).prev().attr('type', 'password')
        $(elem).removeClass('fa-eye-slash');
        $(elem).addClass('fa-eye');
    }else{
        $(elem).prev().attr('type', 'text');
        $(elem).removeClass('fa-eye');
        $(elem).addClass('fa-eye-slash');
    }
}