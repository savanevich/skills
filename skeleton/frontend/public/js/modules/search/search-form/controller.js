'use strict';

module.exports = [
    '$scope', 'UserService', 'TechnologyService', 'LevelService','$state',
    function ($scope, UserService, TechnologyService, LevelService, $state) {
        $scope.$state = $state;
        loadUsers();
        loadTechnologies();
        loadCategories();
        $scope.users = UserService.getUsers();
        $scope.levels = LevelService.getLevels();

        function loadUsers() {
            $scope.users = '';
            UserService.getUsers()
            .then(function(response) {
                $scope.users = response.data;
            });
        }

        function loadTechnologies() {
            $scope.technologies = '';
            var params = null;
            TechnologyService.getTechnologies(params)
            .then(function(response) {
                $scope.technologies = response.data;
            });
        }

        function loadCategories() {
            TechnologyService.getCategories()
            .then(function(response) {
                $scope.categories = response.data;
            })
        }
    }
];
