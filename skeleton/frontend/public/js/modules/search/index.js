'use strict';

var angular = require('angular');

angular
    .module('Search', [])
    .config(require('./config')) 
    .filter('UserFilter', require('./user-filter')) 
    .filter('TechnologyFilter', require('./technology-filter')) 
    .service('TechnologyService', require('./../technology/technology-service'))
    .service('UserService', require('./../user/user-service'))
    .service('LevelService', require('./level-service'))
    .controller('SearchController', require('./search-form/controller'))
    .controller('SearchResultController', require('./search-result/controller'));
