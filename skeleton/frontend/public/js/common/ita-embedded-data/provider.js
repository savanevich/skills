'use strict';

module.exports =  function () {
    var embeddedData = {};

    this.init = function (value) {
        embeddedData = value;
    };

    this.$get = function () {
        return {
            get: function (key) {
                return embeddedData[key] || null;
            },

            getEnvironment: function () {
                return embeddedData.env;
            },

            getCurrentUser: function () {
                return embeddedData.currentUser || null;
            },

            setCurrentUser: function (currentUser) {
                embeddedData.currentUser = currentUser;
            }
        };
    };
};
