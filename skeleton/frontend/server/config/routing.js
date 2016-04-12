'use strict';

var express = require('express'),
    path = require('path');

exports.setup = function (config) {
    var app = config.express;
    var basePath = config.basePath;
    var controllersPath = path.join(basePath, 'controllers');

    // Static path
    app.use(express.static(path.join(basePath, "../public")));

    // Home page
    app.use('/', require(path.join(controllersPath, 'index')));
};
