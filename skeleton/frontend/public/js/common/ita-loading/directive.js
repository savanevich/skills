'use strict';

module.exports = [function () {
        return {
            restrict: 'E',
            replace: true,
            template: require('./template.html'),
            controller: ['$scope', 'itaLoadingService', function($scope, hdLoadingService) {
                $scope.getLoading = function () {
                    return hdLoadingService.getLoading();
                };
            }]
        };
    }
];
