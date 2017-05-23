'use strict';

var domready = require('domready');
var angular = require('angular');
var _ = require('lodash');
var moment = require('moment');

//require common modules
require('./common/ita-embedded-data');
require('./common/ita-request');
require('./common/ita-loading');

//require project modules
require('./modules/search');
require('./modules/user');
require('./modules/technology');

//require custom directives
require('./modules/common');


domready(function () {
    angular
        .module('Skills', [
            'ui.router',
            'ui.bootstrap',
            'satellizer',

            'ITA.EmbeddedData',
            'ITA.Request',
            'ITA.Loading',

            'Common',

            'Search',
            'User',
            'Technology'
        ])
        .config([
            'itaEmbeddedDataServiceProvider',
            'itaRequestServiceProvider',
            '$urlRouterProvider',
            '$authProvider',
            function(itaEmbeddedDataServiceProvider, itaRequestServiceProvider, $urlRouterProvider, $authProvider) {
                itaEmbeddedDataServiceProvider.init(window.embeddedData);
                itaRequestServiceProvider.baseUrl(window.embeddedData.api.url);

                $urlRouterProvider.otherwise('/');
                $authProvider.loginUrl = window.embeddedData.api.url + '/api/v1/auth/login';
                $authProvider.signupUrl = window.embeddedData.api.url + '/api/v1/auth/register';
            }
        ]);

    angular.bootstrap(document, ['Skills']);
});