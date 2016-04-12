'use strict';

var nunjucks = require('nunjucks'),
    path = require('path'),
    favicon = require('serve-favicon');

exports.setup = function(config) {
    var app = config.express;
    var basePath = config.basePath;
    var packageJson = require(path.join(basePath, '../package.json'));

    // view engine setup
    app.set('view engine', 'html');

    app.locals.version = packageJson.version;

    var env = nunjucks.configure(path.join(basePath, 'views'), {
        autoescape: true,
        express: app
    });

    //Add custom filters
    env.addFilter("stringify", function(data) {
        return JSON.stringify(data);
    });

    app.use(favicon(path.join(basePath, '../public/favicon.ico')));
};