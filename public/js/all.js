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


$(document).ready(function ($) {

    if ($('#guild_manage').is('*')) {
        var query = Object.create(UrlQuery);
        var guild = query.getUrlPart();
        // Instantiate the Bloodhound suggestion engine
        var members = new Bloodhound({
            datumTokenizer: function(datum) {
                return Bloodhound.tokenizers.whitespace(datum.value);
            },
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                wildcard: '%QUERY',
                url: 'http://roster.local/public/member/search?q=%QUERY&guild=' + guild,
                transform: function(response) {
                    return $.map(response, function(member) {
                        return {
                            value: member
                        };
                    });
                }
            }
        });

        $('.typeahead').typeahead(null, {
            display: 'value',
            source: members
        });
    }
});

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
                success: function (data) {
                    console.log(data);
                    $('#member_update').dirtyForms('setClean');
                    $('#submit_update').attr('disabled', 'disabled');
                    $('#member_saving').addClass('hidden');
                },
                error: function (data) {
                    console.log(data);
                }
            })
        });
    }
});

// JS code for Member List page

$(document).ready(function ($) {
    if ($('.member-list').is('*')) {
        $('.member-list tbody tr').on('click', function () {
            var id = $(this).attr('data-id');
            document.location = 'member/details/' + id;
        });

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
                // Retrieve coven names into select via AJAX
                $.ajax({
                    type: "GET",
                    url: '/public/member/covens',
                    dataType: 'json',
                    success: function (data) {
                        var covens = $('[aria-label^="Coven"]').find('select');
                        covens.css({ width: '75px' })
                            .empty()
                            .append($('<option>', { value: '', text: 'Coven' }));
                        for (var key in data) {
                            if (data.hasOwnProperty(key)) {
                                var name = data[key];
                                covens.append($('<option>', { value: key, text: name }));
                            }
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    }
                })
            }
        });
    }
});


// This is the app global script.

var UrlQuery = {
    getVar: function (varName) {
        var vars = this.getUrlVars();
        return (vars[varName]);
    },
    getUrlVars: function () {
        var vars = [], hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for (var i = 0; i < hashes.length; i++) {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    },
    getUrlPart: function(index) {
        index = index || - 1;
        var parts = window.location.href.split('/');
        return (index < 0) ? parts.slice(index)[0] : (parts[index]);
    }
}

//# sourceMappingURL=all.js.map
