'use strict';

module.exports = ['$scope', 
				  'SkillsService',
				  'LevelService', 
				  'TechnologyService', 
				  '$state',
				   function ($scope, SkillsService, LevelService, TechnologyService, $state) {

		loadLevels();
		loadCategories();

		$scope.addSkill = function() {
			if ($scope.addSkillForm.$valid) {
				var newSkill = $scope.newSkill;
				var userId = parseInt($scope.currentUser.id, 10);

				$scope.skills = SkillsService.addSkill(userId, newSkill);
				$state.go('settings.skills');
			}
			};

		function loadLevels() {
			$scope.levels = LevelService.getLevels();
		}

		function loadCategories() {
			TechnologyService.getCategories()
			.then(function(response) {
				$scope.categories = response.data;
			});
		}
}];
