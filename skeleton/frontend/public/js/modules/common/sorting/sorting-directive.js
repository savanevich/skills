'use strict';

module.exports = ['$state', '$stateParams', function ($state, $stateParams) {
    return {
        restrict: 'A',
        transclude: true,
        template : require('./template.html'),
        scope:
        {
            field: '=',
            by: '=',
            reverse : '=',
            total: '=',
            limit: '='
        },
        link: function(scope, element, attrs) {
            scope.sortingColumn = $stateParams.sort == null ? scope.field : $stateParams.sort;
            scope.order = $stateParams.order;

            var defineSorting = function () {
                scope.sort = function () {
                    if(scope.total <= scope.limit) {
                        if(scope.field == scope.by) {
                            scope.reverse = !scope.reverse;
                        } else {
                            scope.by = scope.field;
                            scope.reverse = false;
                        }
                    } else {
                        if(scope.sortingColumn != scope.field) {
                            scope.sortingColumn = scope.field;
                            scope.order = '';
                        }
                        scope.order = $stateParams.order === 'asc' ? 'desc' : 'asc';
                        $state.go('.', { sort: scope.sortingColumn, order: scope.order });
                    }
                }
            };

            scope.$watch('total', defineSorting);
        }
    }
}];
