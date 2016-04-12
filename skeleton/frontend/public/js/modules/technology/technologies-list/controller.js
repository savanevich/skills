'use strict';

module.exports = ['$scope',
					'TechnologyService',
					'$stateParams',
					'$location',
			function ($scope,
					  TechnologyService,
					  $stateParams,
					  $location) {

		$scope.page = $stateParams.page;
		$scope.limit = $stateParams.limit;
		loadTechnologies();

		$scope.isNotEmpty = function() {
			return $scope.total > 0;
		};

		function loadTechnologies() {
			$scope.technologies = '';

			//it'll contain get parameters from ui-router
			var getParams = $location.search();

			TechnologyService.getTechnologies(getParams)
			.then(function(response) {
				$scope.technologies = response.data;
				$scope.total = response.total;
			});
		}
	}
];
