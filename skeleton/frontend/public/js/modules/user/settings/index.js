'use strict';

var angular = require('angular');

angular
    .module('User.Settings', ['ngPassword'])
    .config(require('./config'))
    .controller('EditInformationController', require('./edit-information/controller'))
    .controller('EditSkillsController', require('./edit-skills/controller'))
    .controller('AddSkillController', require('./add-skill/controller'))
    .service('UserService', require('./../user-service'))
    .service('AuthService', require('./../auth-service'))
    .service('SkillsService', require('./skills-service'))
    .service('LevelService', require('./../../search/level-service'))
    .service('TechnologyService', require('./../../technology/technology-service'));