'use strict';

angular.module('languageCatalog', []).
    config(
        ['$routeProvider', function($routeProvider) {
            $routeProvider.when('/', {templateUrl: 'partials/landing.html', controller: LandingController});
            $routeProvider.when('/:language', {templateUrl: 'partials/language.html', controller: LanguageController});
            $routeProvider.otherwise({redirectTo: '/'});
        }]
    );
