$(document).ready(function() {
    $('#user_id').select2();
});

$(".time-input").keyup(function () {
    if (this.value.length == this.maxLength) {
        $(this).next('.time-input').focus();
    }
});
