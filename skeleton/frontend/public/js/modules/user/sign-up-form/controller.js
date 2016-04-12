'use strict';

module.exports = ['$scope',
                  'AuthService',
                  '$state',
        function ($scope,
                  AuthService,
                  $state) {

        $scope.user = {};
        $scope.registerError = '';

        $scope.onSubmit = function() {
            if ($scope.signUpForm.$valid) {
                AuthService.signUp($scope.user)
                    .then(function (response) {
                        $state.go('sign-in-form');
                }, function(response) {
                        var errors = response.data.error;
                        errors = errors[Object.keys(errors)[0]];
                        $scope.registerError = errors[0];
                    });
            }
        };
    }
];
