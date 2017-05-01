'use strict';

module.exports = ['itaRequestService', function (itaRequestService) {

        this.getUsers = function(getParams) {

            return itaRequestService.request({
                url: '/api/v1/users/',
                method: 'GET',
                params: getParams
            });
        };

        this.getUser = function(userId) {

            return itaRequestService.request({
                url: '/api/v1/users/' + userId,
                method: 'GET'
            });
        };

        this.followUser = function(userId, followingId) {
            var data = {
                followingId: followingId
            };

            return itaRequestService.request({
                url: '/api/v1/users/' + userId + '/follower',
                method: 'POST',
                params: data
            });
        };

        this.unFollowUser = function(userId, followingId) {
            var data = {
                followingId: followingId
            };

            return itaRequestService.request({
                url: '/api/v1/users/' + userId + '/follower',
                method: 'DELETE',
                params: data
            });
        };
        this.updateUserInformation = function(newInformation, userId) {

            return itaRequestService.request({
                    url: '/api/v1/users/' + userId,
                    method: 'PUT',
                    data: newInformation
                });
        };

        this.getSkills = function(userId) {

            return itaRequestService.request({
                  url: '/api/v1/users/' + userId + '/skills/',
                  method: 'GET'
                });
        };

        this.addSkill = function(userId, skill) {

            return itaRequestService.request({
                  url: '/api/v1/users/' + userId + '/skills/',
                  method: 'POST',
                  data: skill
                });
        };

        this.updateSkill = function(userId, technologyId, skill) {

            return itaRequestService.request({
                    url: '/api/v1/users/' + userId + '/skills/' + technologyId,
                    method: 'PUT',
                    data: skill
                });
        };

        this.deleteSkill = function(userId, technologyId) {

            return itaRequestService.request({
                    url: '/api/v1/users/' + userId + '/skills/' + technologyId,
                    method: 'DELETE'
                });
        };

        this.getUsersByParams = function(getParams) {

            return itaRequestService.request({
                  url: '/api/v1/search/',
                  method: 'GET',
                  params: getParams
                });
        };

        this.getAvatarLink = function(userId) {

            return itaRequestService.request({
                  url: '/api/v1/users/' + userId + '/img/',
                  method: 'GET'
                });
        };

        this.addImage = function(userId, data) {

            return itaRequestService.request({
                  url: '/api/v1/users/' + userId + '/add-img/',
                  method: 'POST',
                  data: data,
                  transformRequest: angular.identity,
                  headers: {'Content-Type': undefined}
                });
        };

        this.getMessages = function(userId) {

            return itaRequestService.request({
                url: '/api/v1/users/' + userId + '/messages/',
                method: 'GET'
            });
        };

        this.sendMessage = function(userId, message) {

            return itaRequestService.request({
                url: '/api/v1/users/' + userId + '/messages/',
                method: 'POST',
                data: message
            });
        };
    }
];
