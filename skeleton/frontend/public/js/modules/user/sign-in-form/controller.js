'use strict';

module.exports =   ['$scope',
                    '$rootScope',
                    'AuthService',
                    '$state',
            function ($scope,
                     $rootScope,
                     AuthService,
                     $state) {

        $scope.user = {};
        $scope.errorLogin = '';

        $scope.onSubmit = function() {
            if ($scope.signInForm.$valid) {
                var credentials = $scope.user;

                AuthService.signIn(credentials)
                    .then(function() {
                        $state.go('search');
                }, function(response) {
                        var errorLogin = response.data.error;
                        if(typeof errorLogin === 'object') {
                            errorLogin = errorLogin[Object.keys(errorLogin)[0]];
                            errorLogin = errorLogin[0];
                        }
                        $scope.errorLogin = errorLogin;

                    });
            }
        };
}];
