'use strict';

var angular = require('angular');
var alertify = require('alertify');

module.exports = function () {
    var baseUrl = '',
        defaultConfig = {
            cache: false,
            showLoading: true,
            withCredentials: false
        };

    this.baseUrl = function (url) {
        baseUrl = url;
    };

    this.$get = [
        '$http',
        '$q',
        'itaEmbeddedDataService',
        'itaLoadingService',
        function ($http, $q, itaEmbeddedDataService, itaLoadingService) {
            var respond = function (deferred, callback) {
                return function (data, status, headers) {
                    itaLoadingService.hideLoading();

                    var contentType = headers('content-type') || '',
                        response;

                    if (contentType.indexOf('application/json') !== 0) {
                        if (itaEmbeddedDataService.getEnvironment() === 'production') {
                            return deferred.reject({
                                meta: {
                                    status: 500,
                                    errors: ['wrong response content type']
                                },
                                data: data
                            });
                        } else {
                            console.log(data);
                        }
                    }

                    if (!data) {
                        return callback();
                    }

                    response = data || {};

                    if (!response.meta) {
                        response.meta = {};
                    }
                    response.meta.headers = headers || null;
                    response.meta.status = status;

                    if (status !== 200 && status !== 201) {
                        if (status === 500 || status === 404) {
                            if (itaEmbeddedDataService.getEnvironment() === 'production') {
                                console.log('An error occurred');
                            } else {
                                console.log(data);
                            }
                        } else if (status === 400 || status === 403) {
                            //TODO: api validation errors
                            response.validationErrors = {};
                        }

                        return deferred.reject(response);
                    }

                    callback(response);
                };
            };

            return {
                baseUrl: function () {
                    return baseUrl;
                },

                request: function (config) {
                    var deferred = $q.defer();

                    config = angular.extend({}, defaultConfig, config);
                    config.url = baseUrl + config.url;

                    if (!config.data) {
                        config.data = {};
                    }
                    //config.data.csrf = embeddedData.getCsrf();

                    if (config.showLoading) {
                        itaLoadingService.showLoading();
                    }

                    $http(config)
                        .success(respond(deferred, deferred.resolve))
                        .error(respond(deferred, deferred.reject));

                    return deferred.promise;
                }
            };
        }
    ];
};
