'use strict';

module.exports = [
    '$scope',
    '$rootScope',
    '$sce',
    '$location',
    '$stateParams',
    '$anchorScroll',
    'UserService',
    'AuthService',
    'SocketFactory',
    function (
        $scope,
        $rootScope,
        $sce,
        $location,
        $stateParams,
        $anchorScroll,
        UserService,
        AuthService,
        SocketFactory
        )
    {
        var userId = parseInt($stateParams.userId, 10);
        $scope.messages = [];
        $scope.link = embeddedData.api.url + '/';

        SocketFactory.on('message', function(data) {
            $scope.$apply(function () {
                var message = JSON.parse(data);
                $scope.messages.push(message);
            });
        });

        $scope.sendMessage = function () {
            var message = {
                'body': $scope.messageBody
            };

            UserService.sendMessage(userId, message)
                .then(function (response) {
                    $scope.messageBody = '';
                    scrollDown()
            })
        };

        loadMessages();

        function scrollDown() {
            $location.hash('bottom');
            $anchorScroll();
        }

        function loadMessages() {
            UserService.getMessages(userId)
                .then(function (response) {
                    if (response.error == false) {
                        $scope.messages = response.data;
                        scrollDown();
                    } else {
                        console.log(response.error);
                    }
                });
        }
    }
];
