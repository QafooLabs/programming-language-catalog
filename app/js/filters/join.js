angular.module('languageCatalog.filters')
.filter('join', function() {
    "use strict";
    /**
     * Simple Filter to join together an array of values using a certain delimiter
     *
     * If no delimiter is specified <tt>, </tt> will be used
     *
     * @param {array} input
     * @param {string|undefined} delimiter
     */
    return function(input, delimiter) {
        if (delimiter === undefined) {
            delimiter = ", ";
        }

        if ( input === undefined || !Array.isArray(input)) {
            return "";
        }

        return input.join(delimiter);
    }
});
