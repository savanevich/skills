'use strict';

module.exports = ['itaRequestService', '$auth', '$q', function (itaRequestService, $auth, $q) {

    var skillsOfCurrentUser = {};
    var promiseOfSkills = undefined;
    var isLastSkillsRequestFailed = true;

    this.getSkills = function(userId) {
        if(!promiseOfSkills || isLastSkillsRequestFailed) {
            promiseOfSkills = itaRequestService.request(
                {
                    url: '/api/v1/users/' + userId + '/skills/',
                    method: 'GET'
                })
                .then(function(response) {
                    isLastSkillsRequestFailed = false;
                    skillsOfCurrentUser = response.data;
                    return skillsOfCurrentUser;
                }, function(response) {
                    isLastSkillsRequestFailed = true;
                    return $q.reject(response);
                });
        }
        return skillsOfCurrentUser;
    };

    this.addSkill = function(userId, skill) {

        addSkillOnApi(userId, skill)
            .then(function(response) {
                skillsOfCurrentUser.push(response.data);
            });

        return skillsOfCurrentUser;
    };

    this.deleteSkill = function(userId, technologyId) {

        removeSkillFromApi(userId, technologyId)
            .then(function() {
            });

        skillsOfCurrentUser = _.remove(skillsOfCurrentUser, function (skill) {
            return skill.id != technologyId;
        });
        return skillsOfCurrentUser;
    };

    this.updateSkill = function(technologyId, skill) {

        updateSkillOnApi(7 , technologyId, skill)
            .then(function() {
                var index = _.indexOf(skillsOfCurrentUser, _.find(skillsOfCurrentUser, function(current) {
                    return current.id == skill.id;
                }));

                skillsOfCurrentUser.splice(index, 1, skill);
            });
        return skillsOfCurrentUser;
    };

    var addSkillOnApi = function(userId, skill) {

        return itaRequestService.request(
            {
                url: '/api/v1/users/' + userId + '/skills/',
                method: 'POST',
                data: skill
            });
    };

    var removeSkillFromApi = function(userId, technologyId) {

        return itaRequestService.request(
            {
                url: '/api/v1/users/' + userId + '/skills/' + technologyId,
                method: 'DELETE'
            })
    };

    var updateSkillOnApi = function(userId, technologyId, skill) {

        return itaRequestService.request(
            {
                url: '/api/v1/users/' + userId + '/skills/' + technologyId,
                method: 'PUT',
                data: skill
            });
    };
}
];
