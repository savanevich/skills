'use strict';

module.exports = [
    '$stateProvider',
    function ($stateProvider) {
        $stateProvider
            .state('search', {
                url: '/',
                controller: 'SearchController',
                template: require('./search-form/template.html')
            })
            .state('search.result-table', {
            	url: 'search?username&technology&level&category&page&limit',
            	controller: 'SearchResultController',
            	template: require('./search-result/template.html'),
                params: {
                    username: {
                        value: null,
                        squash: true
                    },
                    technology: {
                        value: null,
                        squash: true
                    },
                    level: {
                        value: null,
                        squash: true
                    },
                    category: {
                        value: null,
                        squash: true
                    },
                    page: {
                        value: '1',
                        squash: true
                    },
                    limit: {
                        value: '10',
                        squash: true
                    }
                }
            });
    }
];
