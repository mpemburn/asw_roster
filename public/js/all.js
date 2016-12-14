// This is a theoretical utility Javascript file.
// JS code for Member Edit page

$(document).ready(function ($) {
    $('.date-pick').datepicker({
        format: 'M d, yyyy',
        orientation: 'bottom',
    });

    $('#member_degree').on('change', function () {
        $('.degree-date').each(function () {
            var thisDate = $(this);
            var thisDegree = thisDate.attr('data-degree-date');
            var degree = $('#member_degree').val();
            thisDate.removeClass('show hide');
            if (degree >= thisDegree) {
                thisDate.addClass('show');
            } else {
                thisDate.addClass('hide');
            }
        });
    })

    $('#member_updatex').on('submit', function (e) {
        var formAction = this.action;
        $.ajaxSetup({
            header: $('meta[name="_token"]').attr('content')
        });
        e.preventDefault(e);

        $.ajax({
            type: "POST",
            url: formAction,
            data: $(this).serialize(),
            dataType: 'json',
            success: function (data) {
                console.log(data);
            },
            error: function (data) {
                console.log(data);
            }
        })
    });
});

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
