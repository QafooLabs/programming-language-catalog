angular.module('languageCatalog.controllers')
.controller('LanguageController', [
    '$scope', '$routeParams', '$http',
    /**
     * Controller to handle the detailed language page view
     *
     * @param $scope
     * @param $routeParams
     * @param $http
     */
    function ($scope, $routeParams, $http) {
        'use strict';

        // Load the selected language details
        $http.get('languages/' + $routeParams.language + '.json').success(function(data) {
            $scope.language = data;
        });
    }
]);
