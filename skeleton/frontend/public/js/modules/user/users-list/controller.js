'use strict';

module.exports = [  '$scope',
					'UserService',
					'$stateParams',
					'$location',
			function ($scope,
					  UserService,
					  $stateParams,
					  $location) {

		$scope.page = $stateParams.page;
		$scope.limit = $stateParams.limit;
	    loadUsers();

	    $scope.isNotEmpty = function() {
	    	return ($scope.total > 0);
	    };

	    function loadUsers() {
			$scope.users = '';
			$scope.link = embeddedData.api.url + '/';
			var getParams = $location.search();
			UserService.getUsers(getParams)
			.then(function(response) {
				$scope.users = response.data;
				$scope.total = response.total;
			});
		}
    }
];
