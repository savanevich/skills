'use strict';

module.exports = ['AuthService', 'SkillsService', '$state', function (AuthService, SkillsService, $state) {
    	return {
    	    restrict: 'E', 
	        replace: true,
	        template: require('./navbar.html'),
			link: function (scope, element, attrs) {
				scope.currentUser = {};

				scope.logOut = function() {
					AuthService.signOut();
					scope.currentUser = {};
					$state.go('search');
				};

				scope.isAuthenticated = function() {
					return AuthService.isAuthenticated();
				};

				var defineUser = function() {
					if (scope.isAuthenticated()) {
						AuthService.getAuthenticatedUser()
								.then(function (response) {
									scope.currentUser = response;

									return SkillsService.getSkills(scope.currentUser.id);
								})
								.then(function (response) {
									scope.skillsOfCurrentUser = response;
								});
					}
				};

				scope.$watch('isAuthenticated()', defineUser);
			}
		};
	}
];