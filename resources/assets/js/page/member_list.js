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
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });
                var $filter = $('#DataTables_Table_0_filter');
                $filter.parent().removeClass('col-sm-6').addClass('col-sm-2 col-md-4 col-lg-6');
            }
        });
    }
});

