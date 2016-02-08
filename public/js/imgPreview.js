$(document).ready(function() {
    $('input[type=file][accept="image/*"]').change(function(event) {
        var id = $(this).attr('id');
        var output = $('#preview_' + id)[0];
        output.src = URL.createObjectURL(event.target.files[0]);
    });
})