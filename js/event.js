
function hideDeleteConfirmation(e) {
    if (e.target === this) {
        $('#delete-confirmation-wrapper').addClass('hidden');
    }
}

$(function() {
    $('#delete-cancel').click(hideDeleteConfirmation);
    $('#delete-confirmation-wrapper').click(hideDeleteConfirmation);
});

function showDeleteConfirmation() {
    $('#delete-confirmation-wrapper').removeClass('hidden');
}

function hideCompleteConfirmation(e) {
    if (e.target === this) {
        $('#complete-confirmation-wrapper').addClass('hidden');
    }
}

$(function() {
    $('#complete-cancel').click(hideCompleteConfirmation);
    $('#complete-confirmation-wrapper').click(hideCompleteConfirmation);
});

function showCompleteConfirmation() {
    $('#complete-confirmation-wrapper').removeClass('hidden');
}

function hideCreateNotifConfirmation(e) {
    if (e.target === this) {
        $('#create-notif-confirmation-wrapper').addClass('hidden');
    }
}

$(function() {
    $('#create-notif-cancel').click(hideCreateNotifConfirmation);
    $('#create-notif-confirmation-wrapper').click(hideCreateNotifConfirmation);
});

function showCreateNotifConfirmation() {
    $('#create-notif-confirmation-wrapper').removeClass('hidden');
}

function hideArchiveConfirmation(e) {
    if (e.target === this) {
        $('#archive-confirmation-wrapper').addClass('hidden');
    }
}

$(function() {
    $('#archive-cancel').click(hideArchiveConfirmation);
    $('#archive-confirmation-wrapper').click(hideArchiveConfirmation);
});

function showArchiveConfirmation() {
    $('#archive-confirmation-wrapper').removeClass('hidden');
}

function hideUnarchiveConfirmation(e) {
    if (e.target === this) {
        $('#unarchive-confirmation-wrapper').addClass('hidden');
    }
}

$(function() {
    $('#unarchive-cancel').click(hideUnarchiveConfirmation);
    $('#unarchive-confirmation-wrapper').click(hideUnarchiveConfirmation);
});

function showUnarchiveConfirmation() {
    $('#unarchive-confirmation-wrapper').removeClass('hidden');
}

$(function() {
    // from http://stackoverflow.com/questions/45888/what-is-the-most-efficient-way-to-sort-an-html-selects-options-by-value-while
    var my_options = $('.facilities select option');
    var selected = $('.facilities').find('select').val();
  
    my_options.sort(function(a,b) {
      if (a.text > b.text) return 1;
      if (a.text < b.text) return -1;
      return 0
    })
  
    $('.facilities').find('select').empty().append( my_options );
    $('.facilities').find('select').val(selected);
    
    // set it to multiple
    $('.facilities').find('select').attr('multiple', true);
    
    // remove all option
    $('.facilities').find('select option[value=""]').remove();
    // add multiple select checkbox feature.
    $('.facilities').find('select').multiselect();
  })