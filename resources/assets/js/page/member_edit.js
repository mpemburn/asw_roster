// JS code for Member Edit page

$(document).ready(function ($) {

    if ($('#member_update').is('*')) {
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
                emptyValue: ''
            });
        });

        /* Detect any changes to the form data */
        $('#member_update').dirtyForms()
            .on('dirty.dirtyforms clean.dirtyforms', function (ev) {
                var $submitButton = $('#submit_update');
                if (ev.type === 'dirty') {
                    $submitButton.removeAttr('disabled');
                } else {
                    $submitButton.attr('disabled', 'disabled');
                }
            });
        /* Submit form via AJAX */
        $('#member_update').on('submit', function (e) {
            var formAction = this.action;
            $.ajaxSetup({
                header: $('meta[name="_token"]').attr('content')
            });
            e.preventDefault(e);

            $('#member_saving').removeClass('hidden');

            $.ajax({
                type: "POST",
                url: formAction,
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    var success = response.success;
                    $('#member_update').dirtyForms('setClean');
                    if (success.status) {
                        $('#submit_update').attr('disabled', 'disabled');
                        $('#member_saving').addClass('hidden');
                    }
                },
                error: function (response) {
                    console.log(response);
                    $('#member_update').dirtyForms('setClean');
                    if (response.status == '401') {
                        alert(appSpace.authTimeout)
                        location.reload();
                    }
                }
            })
        });
    }
});
