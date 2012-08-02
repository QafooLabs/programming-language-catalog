'use strict';

/**
 * Controller used for the sidebar menu, which contains all the different
 * available programming languages to be accessed
 *
 * @param $scope
 * @param $http
 * @constructor
 */
function SidebarController($scope, $routeParams, $http) {
    // Load all available Language Models
    $http.get('languages/languages.json').success(function(data) {
        $scope.languages = data;
    });

    /**
     * Action used to determine the css class to be used for the given language
     * Uid.
     *
     * The class is mainly used to indicate wheather a certain language is supposed
     * to be active or not.
     *
     * @param language
     * @return {string|null} The CSS class to be used for the nav item
     */
    $scope.navigationClass = function(language) {
        if (language.uid === $routeParams.language) {
            return "active"
        }

        return null;
    }

    // Set the searchQuery to an empty string initially
    $scope.languageSearchQuery = "";
};

// DI injection definition (Only needed if code is minified)
SidebarController.$inject = ['$scope', '$routeParams', '$http'];