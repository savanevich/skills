'use strict';

module.exports = [
    'SocketFactory',
    function (SocketFactory) {
        var typing = false;
        var timeout = undefined;

        function timeoutFunction() {
            typing = false;
            SocketFactory.emit('typing', false);
        }

        return {
            restrict: 'AE',
            link: function (scope, element, attrs) {
                element.bind('keydown keypress', function (event) {

                    if (event.which !== 13) {
                        if (typing === false) {
                            typing = true;
                            SocketFactory.emit('typing', true);
                        } else {
                            clearTimeout(timeout);
                            timeout = setTimeout(timeoutFunction, 5000);
                        }
                    }

                })
            }
        }
    }];