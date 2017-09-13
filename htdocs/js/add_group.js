$(function(){
    $('#schedule').change(function() {
        if ($(this).prop('checked')) {
            $('.schedule').slideDown('fast');
        } else {
            $('.schedule').slideUp('fast');
        }
    });
});
