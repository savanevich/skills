'use strict';

module.exports = ['itaRequestService', '$auth', '$q', function (itaRequestService, $auth, $q) {

    var currentUser = {};
    var promiseOfAuthenticatedUser = undefined;
    var isLastUserRequestFailed = true;

    this.isAuthenticated = function() {

        return $auth.isAuthenticated();
    };

    this.signIn = function(user) {

        return $auth.login(
            {
                email: user.email,
                password: user.password
            });
    };

    this.signOut = function() {
        $auth.logout();
    };

    this.signUp = function(newUser) {

        return $auth.signup({
            username: newUser.username,
            email: newUser.email,
            first_name: newUser.first_name,
            second_name: newUser.second_name,
            password: newUser.password
        });
    };

    this.getAuthenticatedUser = function() {
        if(!promiseOfAuthenticatedUser || isLastUserRequestFailed) {
            promiseOfAuthenticatedUser = itaRequestService.request(
                {
                    url: '/api/v1/auth/user/',
                    method: 'GET'
                })
                .then(function(response) {
                    isLastUserRequestFailed = false;
                    currentUser = response.data.user;
                    return currentUser;
            }, function(response) {
                isLastUserRequestFailed = true;
                return $q.reject(response);
            });
        }
        return promiseOfAuthenticatedUser;
    };

    this.updateUserInformation = function(newInformation, userId) {

        return itaRequestService.request(
            {
                url: '/api/v1/users/' + userId,
                method: 'PUT',
                data: newInformation
            });
    }
}
];
