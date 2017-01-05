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
