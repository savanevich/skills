'use strict';

module.exports = ['$scope', 'LevelService', function ($scope, LevelService) {
    $scope.link = embeddedData.api.url + '/';
    
	$scope.loadSkills = function(userId) {
		$scope.userSkills = $scope.skills[userId];
	};
}];
