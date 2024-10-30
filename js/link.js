$(document).ready(function() {
    var i=0;
    $('#add').click(function() {
        i++;
        $('#dynamic_field').append('<div id="row'+i+'"><label" for="member_'+ i +'">Link '+ i +'</label><input type="text" name="member_' + i + '" value=""><link-tag type="button" class="btn_remove" name="remove" id="'+ i +'">X</link-tag></div>')

    });
    $(document).on('click', '.btn_remove', function() {
        var button_id = $(this).attr("id");
        $('#row' + button_id + '').remove();
    });
});