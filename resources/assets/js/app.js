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
