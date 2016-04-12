'use strict';

module.exports = [
    '$stateProvider',
    function ($stateProvider) {
        $stateProvider
            .state('home', {
                url: '/',
                controller: 'HomeController',
                template: require('./template.html')
            });
    }
];
