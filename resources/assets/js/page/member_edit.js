// JS code for Member Edit page

$(document).ready(function ($) {
    $('.date-pick').datepicker({
        format: 'M d, yyyy',
        orientation: 'bottom'
    });

    $('[name=phone_button]').on('click', function() {
        var value = $(this).val();
        $('[name=Primary_Phone]').val(value);
    });

    /* Use FieldToggle to toggle visibility of date fields */
    var toggler = Object.create(FieldToggle);
    $('#member_degree').on('change', function () {
        toggler.doToggle({
            toggleType: 'select_multi',
            actorSelector: '#member_degree',
            actionSelector: '.degree-date',
            multiAttribute: 'data-degree-date'
        });
    })

    $('#bonded_check').on('click', function () {
        toggler.doToggle({
            toggleType: 'checkbox',
            actorSelector: '#' + $(this).attr('id'),
            actionSelector: '.form-group.bonded-date'
        });
    });

    $('#solitary_check').on('click', function () {
        toggler.doToggle({
            toggleType: 'checkbox',
            actorSelector: '#' + $(this).attr('id'),
            actionSelector: '.form-group.solitary-date'
        });
    });

    $('#leadership-role').on('change', function () {
        toggler.doToggle({
            toggleType: 'select',
            actorSelector: '#' + $(this).attr('id'),
            actionSelector: '.form-group.leadership-date',
            emptyValue: '0'
        });
    });

    $('#board-role').on('change', function () {
        toggler.doToggle({
            toggleType: 'select',
            actorSelector: '#' + $(this).attr('id'),
            actionSelector: '.form-group.expiry-date',
            emptyValue: '0'
        });
    });

    /* Submit form via AJAX */
    $('#member_update').on('submit', function (e) {
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
