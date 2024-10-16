$(function() {
    $('tr.message').click(function() {
        let id = $(this).data('message-id');
        window.location = 'event.php?id=' + id;
    });

    $('#delete-button').click(function() {
        let id = $(this).data('message-id');
        sendDelete(id);
    });
});