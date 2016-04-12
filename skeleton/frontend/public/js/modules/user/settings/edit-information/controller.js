'use strict';

module.exports = [
    '$scope',
    'AuthService',
    'UserService',
    function ($scope,
              AuthService,
              UserService) {

        $scope.showMsgAfterSuccessfullyUpdatingAvatar = false;
        $scope.showMsgAfterSuccessfullyUpdatingInformation = false;
        $scope.updatingInfoError = '';

        AuthService.getAuthenticatedUser()
            .then(function(response){
                $scope.currentUser = response;
                loadAvatar($scope.currentUser.id)
            });

        $scope.trustSrc = function (src) {
            return $sce.trustAsResourceUrl(src);
        };

        $scope.uploadAvatarImage = function (e) {
            var uploadForm = document.querySelector('#upload');
            var formData = new FormData(uploadForm);
            UserService.addImage($scope.currentUser.id, formData)
                .then(function(data){
                    if (data.status === 200) {
                        $scope.link = embeddedData.api.url + '/' + data.data;
                        $scope.showMsgAfterSuccessfullyUpdatingAvatar = true;
                    }
                });
            return false;
        };

        $scope.updateProfile = function() {
            var newInformation = $scope.currentUser;

            UserService.updateUserInformation(newInformation, $scope.currentUser.id)
                .then(function(response) {
                    $scope.showMsgAfterSuccessfullyUpdatingInformation = true;
                }, function(response) {
                    var errors = response;
                    errors = errors[Object.keys(errors)[0]];
                    $scope.updatingInfoError = errors[0];
                });
        };

        function loadAvatar(id) {
            $scope.link = '';
            UserService.getAvatarLink(id)
                .then(function (response) {
                    $scope.link = embeddedData.api.url + '/' + response.data;
                });
        }
    }
];