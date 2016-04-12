'use strict';

var angular = require('angular');

angular.module('ITA.Request', [])
    .provider('itaRequestService', require('./provider'))
    .config(require('./config'));
