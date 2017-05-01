'use strict';

module.exports = ['$stateProvider', function ($stateProvider) {

        $stateProvider
            .state('sign-up-form', {
                url: '/sign-up-form',
                controller: 'SignUpFormController',
                template: require('./sign-up-form/template.html')
            })
            .state('sign-in-form', {
                url: '/sign-in-form',
                controller: 'SignInFormController',
                template: require('./sign-in-form/template.html')
            })
            .state('users', {
                url: '/users?page&limit&sort&order',
                controller: 'UserController',
                template: require('./users-list/template.html'),
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
            })
            .state('users.profile', {
                url: '/:userId',
                views: {
                    // rule for absolutely targetting the unnamed view in root unnamed state.
                    "@": {
                        controller: 'ProfileController',
                        template: require('./profile/template.html')
                    }
                }
            })
            .state('users.profile.messages', {
                url: '/messages',
                views: {
                    // rule for absolutely targetting the unnamed view in root unnamed state.
                    "@": {
                        controller: 'ChatController',
                        template: require('./chat/template.html')
                    }
                }
            });
    }
];
