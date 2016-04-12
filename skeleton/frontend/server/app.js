'use strict';

var express = require('express');
var app = express();

var initialisers = [
    require('./config/http'),
    require('./config/routing'),
    require('./config/templating')
];

var config = {
    express: app,
    basePath: __dirname
};

initialisers.forEach(function(initializer) {
    initializer.setup(config);
});

module.exports = app;