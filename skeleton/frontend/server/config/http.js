'use strict';

var cookieParser = require('cookie-parser'),
    bodyParser = require('body-parser'),
    session = require('cookie-session'),
    //TODO: move to config file
    cookieKeys = ['cookie', 'session', 'keys'];

exports.setup = function(config) {
    var app = config.express;

    app.use(bodyParser.json());
    app.use(bodyParser.urlencoded({ extended: false }));
    app.use(cookieParser());

    app.use(session({
        keys: cookieKeys
    }));
};