// This is a theoretical utility Javascript file.
// JS code for Member List page

$(document).ready(function ($) {
    if ($('.member-list').is('*')) {
        $('.member-list tbody tr').on('click', function() {
            var id = $(this).attr('data-id');
            document.location = 'member/details/' + id;
        })
    }
});


// This is the app global script.
//# sourceMappingURL=all.js.map
