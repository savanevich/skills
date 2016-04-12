'use strict';

module.exports = ['$stateProvider', function ($stateProvider) {

    $stateProvider
        .state('settings', {
            url: '/settings',
            controller: function($state) {
                $state.go('settings.information');
            },
            template: require('./settings-layout/template.html')
        })
        .state('settings.information', {
            url: '/profile',
            controller: 'EditInformationController',
            template: require('./edit-information/template.html')
        })
        .state('settings.skills', {
            url: '/skills',
            controller: 'EditSkillsController',
            template: require('./edit-skills/template.html')
        })
        .state('settings.skills.add-skill', {
            url: '/add-skill',
            controller: 'AddSkillController',
            template: require('./add-skill/template.html')
        });
}
];
