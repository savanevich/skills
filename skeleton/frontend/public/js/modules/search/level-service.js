'use strict';
module.exports = [function () {
        
        var levels = [
            {
                number: 1,
                name: 'Newbie',                
            },
            {
                number: 2,
                name: 'Beginner',                
            },
            {
                number: 3,
                name: 'Delitant',                
            },
            {
                number: 4,
                name: 'Verced',                
            },
            {
                number: 5,
                name: 'Advanced',                
            },
            {
                number: 6,
                name: 'Experienced',                
            },
            {
                number: 7,
                name: 'Skilled',                
            },
            {
                number: 8,
                name: 'Specialist',                
            },
            {
                number: 9,
                name: 'Veteran',                
            },
            {
                number: 10,
                name: 'Expert',                
            }
        ];

        this.getLevels = function () {
            return levels;
        };

        this.getLevel = function(number) {
            return _.find(levels, function(level) {
                return level.number === number;
            });
        };
    }
];
