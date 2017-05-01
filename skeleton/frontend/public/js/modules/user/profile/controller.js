'use strict';

module.exports = [
	'$scope',
	'$rootScope',
	'$sce',
	'UserService',
	'AuthService',
	'$stateParams',
	function (
		$scope,
		$rootScope,
		$sce,
		UserService,
		AuthService,
		$stateParams)
	{
		var userId = parseInt($stateParams.userId, 10);
		$scope.parentobj = {};
		$scope.parentobj.skills = {};
		$scope.user = '';
		$scope.allowEditProfile = false;

		loadUser(userId);
		loadSkills(userId);
		loadAvatar(userId);

		$scope.isHaveSkills = function () {
			return !$rootScope.progressing && $scope.parentobj.skills.length > 0;
		};

		$scope.showMsgIfNoSkills = function () {
			return !$rootScope.progressing && $scope.parentobj.skills.length === 0;
		};

		$scope.followUser = function () {
			UserService.followUser($scope.currentUser.id, $scope.user.id)
				.then(function (response) {
					$scope.user.followers.push(response.data.follower_id);
					$scope.user.followers_count++;
					$scope.currentUser.following_count++;
				});
		};

		$scope.unFollowUser = function () {
			UserService.unFollowUser($scope.currentUser.id, $scope.user.id)
				.then(function () {
					var index = $scope.user.followers.indexOf($scope.currentUser.id);

					if (index > -1) {
						$scope.user.followers.splice(index, 1);
					}

					$scope.user.followers_count--;
					$scope.currentUser.following_count--;
				});
		};

		$scope.isAuthenticated = function() {
			return AuthService.isAuthenticated();
		};

		function loadUser(id) {
			if ($scope.currentUser.id != id) {
				UserService.getUser(parseInt(id, 10)).then(function (response) {
					$scope.user = response.data;
					loadAvatar(id);
				});
			} else {
				$scope.user = $scope.currentUser;
				$scope.allowEditProfile = true;
				loadAvatar(id);
			}
		}

		function loadSkills(id) {
			UserService.getSkills(id)
				.then(function (response) {
					if (response.error == false) {
						$scope.parentobj.skills = response.data;
					} else {
						console.log(response.error);
					}
				});
		}

		function loadAvatar(id) {
			$scope.link = '';
			UserService.getAvatarLink(id)
				.then(function (response) {
					$scope.link = embeddedData.api.url + '/' + response.data;
				});
		}
	}
];
