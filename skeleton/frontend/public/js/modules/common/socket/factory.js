'use strict';

module.exports = ['$rootScope', function($rootScope) {
    var socket = io.connect('http://192.168.55.55:3020');

    return {
        on: function(eventName, callback){
            socket.on(eventName, callback);
        },
        emit: function(eventName, data) {
            socket.emit(eventName, data);
        }
    };
}];