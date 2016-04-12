'use strict';

module.exports = ['$scope', 'UserService', '$location', '$stateParams',
    function ($scope, UserService, $location, $stateParams) {

        $scope.page = $stateParams.page;
        $scope.limit = $stateParams.limit;
        $scope.error = '';
        loadUsersByParams();

        $scope.isNotEmpty = function() {
            return ($scope.total > 0);
        };

        function loadUsersByParams() {
            var getParams = $location.search();

            UserService.getUsersByParams(getParams)
            .then(function(response) {
                    $scope.selectedUsers = response.data.users;
                    $scope.skills = response.data.skills;
                    $scope.total = response.data.total;
            }, function(response) {
                $scope.error = response.error;
            });
        }
}];
