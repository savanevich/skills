'use strict';

module.exports = [function () {
    	return {
    	    restrict: 'E', 
	        replace: true,
	        template: require('./breadcrumbs.html'),
			link: function (scope, element, attrs) {

				var setNavigationState = function(state) {
					var currentStateName = state;

					var path = [];

					while (currentStateName.length > 0) {

						var sectionName = currentStateName.substring(currentStateName.lastIndexOf('.') + 1);
						sectionName = sectionName.charAt(0).toUpperCase() + sectionName.substr(1);
						var name = sectionName.replace("-", " ");

						path.push({
							'name': name,
							'state': currentStateName
						});

						currentStateName = currentStateName.substring(0, currentStateName.lastIndexOf('.'));
					}

					scope.path = path.reverse();
				};

				scope.$on('$stateChangeSuccess', function (event, toState) {
					setNavigationState(toState.name);
				});
			}
    	};
    }
];
