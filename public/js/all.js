/* FieldToggle provides support for toggling visibility of one to several fields
 * Usage: Place in listener for the 'actor' field
 *
 var fieldToggle = Object.create(FieldToggle);
 fieldToggle.doToggle({
     toggleType: 'select',
     actorSelector: '#' + $(this).attr('id'),
     actionSelector: '.form-group.leadership-date',
     emptyValue: '0'
 });
 *
 *
 * */
var FieldToggle = {
    toggleType: null,
    actorSelector: null,
    actionSelector: null,
    emptyValue: null,
    multiAttribute: null,
    doToggle: function(options) {
        $.extend(this, options);
        switch (this.toggleType) {
            case 'checkbox':
                this._doCheckbox();
                break;
            case 'select':
                this._doSelect();
                break;
            case 'select_multi':
                this._doSelectMulti();
                break;
        }

    },
    _doCheckbox: function() {
        var $thisActor = $(this.actorSelector);
        var $thisAction = $(this.actionSelector);
        var toggle = ($thisActor.is(':checked')) ? 'show' : 'hide';
        $thisAction.removeClass('show hide');
        $thisAction.addClass(toggle);
    },
    _doSelect: function() {
        var $thisActor = $(this.actorSelector);
        var $thisAction = $(this.actionSelector);
        var toggle = ($thisActor.val() != this.emptyValue) ? 'show' : 'hide';
        $thisAction.removeClass('show hide');
        $thisAction.addClass(toggle);
    },
    _doSelectMulti: function() {
        var self = this;
        $(this.actionSelector).each(function () {
            var $this = $(this);
            var thisValue = $this.attr(self.multiAttribute);
            var currentVal = $(self.actorSelector).val();
            var toggle = (currentVal >= thisValue) ? 'show' : 'hide';
            $this.removeClass('show hide');
            $this.addClass(toggle);
        });
    }
}


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

// JS code for Member List page

$(document).ready(function ($) {
    if ($('.member-list').is('*')) {
        $('.member-list tbody tr').on('click', function () {
            var id = $(this).attr('data-id');
            document.location = 'member/details/' + id;
        })

        $('.member-list').DataTable({
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            iDisplayLength: -1,
            aaSorting: [],
            initComplete: function () {
                this.api().columns('.filterable').every(function (index) {
                    var column = this;
                    var header = column.header();
                    var select = $('<select><option value="">' + header.innerHTML + '</option></select>')
                        .appendTo($(column.header()).empty())
                        .on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                    column.data().unique().sort().each(function (d, j) {
                        var label = (d == '') ? 'All' : d;
                        select.append('<option value="' + d + '">' + label + '</option>')
                    });
                });
            }
        });
    }
});


// This is the app global script.
//# sourceMappingURL=all.js.map
