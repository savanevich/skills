'use strict';

module.exports = ['LevelService', function(LevelService) {
    return function (number) {
    	var skill = LevelService.getLevel(parseInt(number));
    	
        return skill.number + ' ' + skill.name;
    }
}];