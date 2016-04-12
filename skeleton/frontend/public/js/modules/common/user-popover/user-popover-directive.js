'use strict';

module.exports = [function () {
    return {
        restrict: 'E',
		scope: {
				user:'=',
				skills: '='
				},
        template: require('./template.html'),
        controller: require('./controller.js')
    };
}];
