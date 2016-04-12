'use strict';

var angular = require('angular');

angular
    .module('Technology', [])
    .config(require('./config'))
    .controller('TechnologyController', require('./technologies-list/controller'))
    .service('TechnologyService', require('./technology-service'));