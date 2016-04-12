'use strict';

module.exports = ['$rootScope', '$state', 'AuthService', function($rootScope, $state, AuthService) {

    $rootScope.$on('$stateChangeStart', function(event, next) {

        if(AuthService.isAuthenticated()) {
            if(next.name === "sign-up-form" || next.name === "sign-in-form") {
                event.preventDefault();
                $state.go('search');
            }
        } else {
            if(next.name === "settings" ||
               next.name === "settings.information" ||
               next.name === "settings.skills" ||
               next.name === "settings.skills.add-skill'") {
                event.preventDefault();
                $state.go('sign-in-form');
            }
        }
    });
}];
