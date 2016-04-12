'use strict';

module.exports = [function () {
	  	return {
	    	restrict: 'E',
	    	template: require('./typeahead.html'),
	    	controller: require('./controller.js'),
	    	scope: {
	      		items: '=',
	      		prompt: '@',
	      		property: '@',
	      		model: '='
	    	},
	    	link: function(scope, elem, attrs) {}   
  	};
}];