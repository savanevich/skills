'use strict';

var angular = require('angular');

require('./settings/index');

angular
    .module('User', ['ngPassword', 'User.Settings'])
    .config(require('./config'))
    .run(require('./auth-run'))
    .controller('UserController', require('./users-list/controller'))
    .controller('ProfileController', require('./profile/controller'))
    .controller('SignUpFormController', require('./sign-up-form/controller'))
    .controller('SignInFormController', require('./sign-in-form/controller'))
    .service('UserService', require('./user-service'))
    .service('AuthService', require('./auth-service'))
    .service('LevelService', require('./../search/level-service'))
    .service('TechnologyService', require('./../technology/technology-service'));
    