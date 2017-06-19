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
        var chatId = null;
        $scope.messages = [];
        $scope.link = embeddedData.api.url + '/';
        $scope.isTyping = false;
        $scope.moment = moment;

        if (userId && $scope.currentUser.id && $scope.currentUser.id > userId) {
            chatId = $scope.currentUser.id + '_' + userId;
        } else if (userId && $scope.currentUser.id) {
            chatId = userId + '_' + $scope.currentUser.id;
        }

        SocketFactory.emit('join', {
            chatId: chatId,
            userName: $scope.currentUser.username
        });

        SocketFactory.on('message', function (data) {
            $scope.$apply(function () {
                $scope.isTyping = false;
                $scope.messages.push(data);
                scrollDown();
            })
        });

        SocketFactory.on('isTyping', function (data) {
            $scope.$apply(function () {
                if (data.chat == chatId) {
                    $scope.isTyping = data.isTyping;
                    $scope.typedUserName = data.person;
                }
            });
            $scope.$apply();
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
