'use strict';

module.exports = [ function () {
    return {
            restrict: 'E',
            replace: true,
            scope:
            {
                limit:'=',
                total: '=',
                page: '='
            },
            template: require('./template.html'),
            link: function (scope, element, attrs) {

                var calcPages = function () {
                    var limit = scope.limit;
                    var total = scope.total;
                    var totalSections = Math.ceil(total / limit);
                    if(totalSections < 1 && limit < 1 && total < 1) {
                        return false;
                    }

                    var sections = new Array();
                    for(var i = 0, j = 0; i < totalSections; i++) {
                        if(i == 0 || i == totalSections - 1 || (i < parseInt(scope.page) + 2 && i > parseInt(scope.page) - 4)) {
                            sections[j] = i+1;
                            ++j;
                        } else if((i == (parseInt(scope.page) + 2)) || (i == (parseInt(scope.page) - 4))) {
                            sections[j] = '...';
                            ++j;
                        }
                    }
                    scope.page = scope.page || 1;
                    scope.limit = parseInt(scope.limit, 10) || 10;
                    scope.totalSections = totalSections;
                    scope.total = parseInt(scope.total, 10) || 0;
                    scope.sections = sections;
                };

                scope.$watch('total', calcPages);
                scope.$watch('limit', calcPages);
                scope.$watch('page', calcPages);
            }
        };
    }];
