#!/usr/bin/env node
'use strict';

var app = require('./server/app');
var server = require('http').Server(app);
var io = require('socket.io')(server);
var redis = require('redis');
var fs = require('fs');

app.set('port', process.env.PORT || 3020);
app.set('env', process.env.NODE_ENV || 'local');

io.on('connection', function (socket) {
    var redisClient = redis.createClient();

    redisClient.subscribe('message');

    redisClient.on('message', function(channel, message) {
        socket.emit(channel, message);
    });

    socket.on('disconnect', function() {
        redisClient.quit();
    });
});

server.listen(app.get('port'), function() {
    console.log('Skills web server listening on port ' + server.address().port);
});
