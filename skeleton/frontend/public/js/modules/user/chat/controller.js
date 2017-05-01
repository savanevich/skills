'use strict';

module.exports = [
    '$scope',
    '$rootScope',
    '$sce',
    '$location',
    '$stateParams',
    'UserService',
    'AuthService',
    function (
        $scope,
        $rootScope,
        $sce,
        $location,
        $stateParams,
        UserService,
        AuthService
        )
    {
        var userId = parseInt($stateParams.userId, 10);
        $scope.messages = [];
        $scope.link = embeddedData.api.url + '/';

        $scope.sendMessage = function () {
            var message = {
                'body': $scope.messageBody
            };

            UserService.sendMessage(userId, message)
                .then(function (response) {
                    $scope.messages.push(response.data);
                    $scope.messageBody = '';
                    $location.hash('message-block');
                    $anchorScroll();
            })
        };

        loadMessages();

        function loadMessages() {
            UserService.getMessages(userId)
                .then(function (response) {
                    if (response.error == false) {
                        $scope.messages = response.data;
                    } else {
                        console.log(response.error);
                    }
                });
        }
    }
];
