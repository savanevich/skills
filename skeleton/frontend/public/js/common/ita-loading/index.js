'use strict';

var angular = require('angular');

angular
    .module('ITA.Loading', [])
    .directive('itaLoading', require('./directive'))
    .provider('itaLoadingService', require('./provider'));
