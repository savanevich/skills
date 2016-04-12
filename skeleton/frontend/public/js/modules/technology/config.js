'use strict';

module.exports = ['$stateProvider', function ($stateProvider) {

        $stateProvider
            .state('technologies', {
                url: '/technologies?page&limit&sort?order',
                controller: 'TechnologyController',
                template: require('./technologies-list/template.html'),
                params: {
                    page: {
                        value: '1',
                        squash: true
                    },
                    limit: {
                        value: '10',
                        squash: true
                    },
                    sort: {
                        value: undefined,
                        squash: true
                    },
                    order: {
                        value: 'false',
                        squash: true
                    }
                }
            });
    }
];
