'use strict';

module.exports = [
    '$scope',
    '$rootScope',
    'AuthService',
    'SkillsService',
    'LevelService',
    'TechnologyService',
    '$state',
    function ($scope,
              $rootScope,
              AuthService,
              SkillsService,
              LevelService,
              TechnologyService,
              $state) {


        $scope.$state = $state;
        var loadedLevel = null;
        var loadedName = null;
        var loadedCategory = null;
        $scope.skillsOfCurrentUser = [];
        $scope.editedSkill = null;
        $scope.levels = LevelService.getLevels();
        loadCategories();
        loadSkills($scope.currentUser.id);

        $scope.isHaveSkills = function () {
            return !$rootScope.progressing  && $scope.skillsOfCurrentUser.length > 0;
        };

        $scope.showMsgIfNoSkills = function() {
            return !$rootScope.progressing && $scope.skillsOfCurrentUser.length == 0;
        };

        function loadSkills(userId) {
            $scope.skillsOfCurrentUser = SkillsService.getSkills(userId);
        }
        $scope.deleteSkill = function(id) {
            var technology_id = id;
            if(confirm('Are you sure you want to delete this?')) {
                $scope.skillsOfCurrentUser = SkillsService.deleteSkill($scope.currentUser.id, parseInt(technology_id, 10));
            }
        };

        $scope.editSkill = function(technologyId, name, categoryName, skill) {
            skill.pivot.level = LevelService.getLevel(parseInt(skill.pivot.level));
            loadedLevel = skill.pivot.level;
            loadedName = name;
            loadedCategory = categoryName;
            $scope.editedSkill = technologyId;
        };

        $scope.cancelEditSkill = function(skill) {
            skill.pivot.level = loadedLevel;
            skill.category.name = loadedCategory;
            skill.name = loadedName;
            skill.pivot.level = skill.pivot.level.number;
            $scope.editedSkill = null;
        };

        $scope.updateSkill = function (skill) {
            var technologyId = parseInt(skill.pivot.technology_id, 10);
            skill.pivot.level = skill.pivot.level.number;

            $scope.skillsOfCurrentUser = SkillsService.updateSkill(technologyId, skill);
            $scope.editedSkill = null;
        };

        function loadCategories() {
            TechnologyService.getCategories()
                .then(function(response) {
                    $scope.categories = response.data;
                })
        }
    }
];