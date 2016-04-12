'use strict';

module.exports = [function () {
        
        return function(technologies,input) {
            var newTechnologynames = [];
            var searchInput = angular.lowercase(input);
            technologies.forEach(function(technology){
                var technologyname = angular.lowercase(technology.name);
                if (technologyname.indexOf(input) === 0){
                    newTechnologynames.push(technology);
                } 
            });
            return newTechnologynames; 

        }
    }
];
