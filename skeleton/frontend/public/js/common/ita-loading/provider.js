'use strict';

var angular = require('angular');

module.exports = [function () {
    var showLoading = false;

    this.$get = ['$window', '$document', '$compile', '$rootScope', function ($window, $document, $compile, $rootScope) {
        var findLoadingElement = function () {
                return angular.element($document.find('#ita-loading'));
            },
            loadingElement = findLoadingElement();

        return {
            initLoading: function () {
                if (loadingElement.length !== 0) {
                    return false;
                }

                var bodyElement = angular.element($window.document.body),
                    scope = $rootScope.$new();

                loadingElement = angular.element($compile('<hd-loading></hd-loading>')(scope));

                bodyElement.append(loadingElement);

                return true;
            },

            destroyLoading: function () {
                loadingElement.remove();
                loadingElement = findLoadingElement();
            },

            showLoading: function () {
                this.initLoading();
                $rootScope.progressing = true;
                showLoading = true;
            },

            hideLoading: function (destroy) {
                showLoading = false;
                $rootScope.progressing = false;
                if (destroy) {
                    this.destroyLoading();
                }
            },

            getLoading: function () {
                return showLoading;
            }
        };
    }];
}];
