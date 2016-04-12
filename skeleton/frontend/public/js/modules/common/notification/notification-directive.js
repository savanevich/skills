'use strict';

module.exports = [function () {
    	return {
    	    restrict: 'E', 
	        replace: true,
	    	template: require('./notification.html'),
	    	scope: {
            	message: '@',
            	notificationType: '@'
        	}
    	};
    }
];
