'use strict';

module.exports = ['$scope','itaRequestService', function ($scope, itaRequestService) {       
        $scope.model = $scope.model || '';

        $scope.selectItem = function(element) {
        	$scope.model = element;
        	$scope.selected = true;
        }

        $scope.itemFilter = function(item) {
                if(item[$scope.property].toLowerCase().indexOf($scope.model.toLowerCase()) === 0)
                return item[$scope.property];
        }; 	
  }
];
