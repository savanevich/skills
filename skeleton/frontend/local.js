#!/usr/bin/env node
'use strict';

var https = require('https');
var app = require('./server/app');
var fs = require('fs');

app.set('port', process.env.PORT || 3020);
app.set('env', process.env.NODE_ENV || 'local');

var server = app.listen(app.get('port'), function() {
    console.log('Skills web server listening on port ' + server.address().port);
});
