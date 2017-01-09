var TableManager = {
    typeaheadUrl: '',
    addUrl: '',
    removeUrl: '',
    idName: '',
    tableSelector: '',
    searchSelector: '',
    addSelector: '',
    removeSelector: '',
    table: null,
    search: null,
    add: null,
    remove: null,
    bloodhound: null,
    onTableComplete: function(){},
    ajaxCallback: function(){},
    init: function(options) {
        $.extend(this, options);
        this._setTable();
        this._setBloodhound();
        this._setTypeahead();
        this._setListeners();
    },
    _doAjax: function(url, idParam, ajaxCallback) {
        this.ajaxCallback = ajaxCallback;
        var self = this;
        $.ajax({
            type: "GET",
            url: url + '&' + idParam,
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    if (response.data) {
                        self.ajaxCallback(response.data);
                    }
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    },
    _onAddComplete: function(data) {
        var newRow = this.table.row.add([
                data.name,
                data.phone,
                '<a href="mailto:' + data.email + '">' + data.email + '</a>',
                data.coven,
                '<i class="fa fa-close guild-remove"></i>'
            ])
            .draw()
            .node();
        // Add the data-id attribute to the newly created row
        $(newRow).attr('data-id', data.member_id);
        // Add the 'remove' icon
        var $remove = $(newRow).find('i').parent();
        $remove.addClass('remove');
        // Refresh remove listener
        this._setListenerRemove();
        // Disable add button and clear search field
        this.search.typeahead('val', '');
        this.add.attr('disabled', 'disabled');
    },
    _onRemoveComplete: function() {

    },
    _setBloodhound: function() {
        var self = this;
        this.bloodhound = new Bloodhound({
            datumTokenizer: function (datum) {
                return Bloodhound.tokenizers.whitespace(datum.value);
            },
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                wildcard: '%QUERY',
                url: self.typeaheadUrl,
                transform: function (response) {
                    // Populate typeahead list with returned data
                    return $.map(response, function (data) {
                        return {
                            id: data.id,
                            value: data.value
                        };
                    });
                }
            }
        });
    },
    _setListeners: function() {
        this._setListenerAdd();
        this._setListenerRemove();
    },
    _setListenerAdd: function() {
        var self = this;
        this.add = $(this.addSelector);
        this.add.on('click', function () {
            var idValue = self.search.data(self.idName);
            self._doAjax(self.addUrl, self.idName + '=' + idValue, self._onAddComplete);
        });
    },
    _setListenerRemove: function() {
        var self = this;
        this.remove = $(this.removeSelector);
        this.remove.off().on('click', function (evt) {
            evt.stopPropagation();
            var $row = $(this).closest('tr');
            var idValue = $row.attr('data-id');
            $row.hide();
            self.table
                .row( $(this).parents('tr') )
                .remove()
                .draw();
            self._doAjax(self.removeUrl, self.idName + '=' + idValue, self._onRemoveComplete);
        });
    },
    _setTable: function() {
        var self = this;
        this.table = $(this.tableSelector).DataTable({
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]], // Number of entries to show
            iDisplayLength: -1,
            aaSorting: [],
            columnDefs: [
                {orderable: false, targets: 4}
            ],
            initComplete: function () {
                var $search = $($(this).selector + '_filter').find('input[type="search"]');
                // Add 'clearable' x to search field, and callback to restore table on clear
                $search.clearable({
                    onClear: function() {
                        self.table.search( '' ).columns().search( '' ).draw();
                    }
                });
                // Add filter dropdowns to dataTables.js header
                var addFilters = Object.create(AddColumnFilters);
                addFilters.init({dataTables: this});
                // Do callback
                self.onTableComplete();
            }
        });
    },
    _setTypeahead: function() {
        var self = this;
        this.search = $(this.searchSelector);
        this.search.typeahead(null, {
            name: 'id',
            display: 'value',
            source: this.bloodhound,
            hint: true,
            highlight: true,
            limit: Infinity,
        }).on('typeahead:selected', function (evt, data) {
            self.search.data(self.idName, data.id);
            self.add.removeAttr('disabled');
        }).on('input', function () {
            if ($(this).val() == '') {
                self.add.attr('disabled', 'disabled');
            }
        }).clearable({
            onClear: function(target) {
                // Clear typeahead and disable the add button
                target.typeahead('val', '');
                self.add.attr('disabled', 'disabled');
            }
        });
    }
};

$(document).ready(function ($) {
    if ($('#guild_manage').is('*')) {
        var guild = appSpace.urlQuery.getUrlPart();
        var guildTable = Object.create(TableManager);
        guildTable.init({
            typeaheadUrl: appSpace.baseUrl + '/member/search?q=%QUERY&guild_id=' + guild,
            addUrl: appSpace.baseUrl + '/guild/add?guild_id=' + guild,
            removeUrl: appSpace.baseUrl + '/guild/remove?guild_id=' + guild,
            idName: 'member_id',
            tableSelector: '#guild_member_list',
            searchSelector: '#guild_search',
            addSelector: '#guild_add_member',
            removeSelector: '.guild-remove',
            onTableComplete: function() {
                // Retrieve coven names into select via AJAX
                var reviseSelect = Object.create(ReviseSelect);
                reviseSelect.init({
                    ajaxUrl: '/public/member/covens',
                    selector: '[aria-label^="Coven"]',
                    width: '75px',
                    isChild: true,
                    prepend: {value: '', text: 'Coven'},
                    useOriginalValues: true
                });
            }
        });
    }
});
