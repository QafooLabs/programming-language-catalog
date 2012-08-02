'use strict';

// Main Application module
angular.module('languageCatalog', ['languageCatalog.controllers', 'languageCatalog.filters'])
.config([
    '$routeProvider',
    /**
     * Initialize the application registering all the needed routes
     *
     * @param $routeProvider
     */
    function($routeProvider) {
        $routeProvider.when('/', {templateUrl: 'partials/landing.html', controller: 'LandingController'});
        $routeProvider.when('/:language', {templateUrl: 'partials/language.html', controller: 'LanguageController'});

        $routeProvider.otherwise({redirectTo: '/'});
    }
]);
