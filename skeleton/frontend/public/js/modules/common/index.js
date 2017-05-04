'use strict';

var angular = require('angular');

angular
    .module('Common', [])
    .directive('appFooter', require('./footer/footer-directive'))
    .directive('appNavbar', require('./navbar/navbar-directive'))
    .directive('breadcrumbs', require('./breadcrumbs/breadcrumps-directive'))
    .directive('userPopover', require('./user-popover/user-popover-directive'))
    .directive('appPagination', require('./pagination/pagination-directive'))
    .directive('notification', require('./notification/notification-directive'))
    .directive('sorting', require('./sorting/sorting-directive'))
    .directive('loadingIndicator', require('./loading-spinner/loading-spinner-directive'))
    .directive('autocomplete', require('./typeahead/typeahead-directive'))
    .service('LevelService', require('./../search/level-service'))
    .factory('SocketFactory', require('./socket/factory'))
    .filter('levelFormat', require('./skill-format/skill-format-filter'));
