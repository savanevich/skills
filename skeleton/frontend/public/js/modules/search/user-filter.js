'use strict';

module.exports = [function () {
        
        return function(users,input) {
            var newUsernames = [];
            var searchInput = angular.lowercase(input);
            users.forEach(function(user){
                var username = angular.lowercase(user.username);
                if (username.indexOf(searchInput) === 0){
                    newUsernames.push(user);
                } 
            });
            return newUsernames; 

        }
    }
];
