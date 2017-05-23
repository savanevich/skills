#!/usr/bin/env node
'use strict';

var app = require('./server/app');
var server = require('http').Server(app);
var io = require('socket.io')(server);
var redis = require('redis');
var fs = require('fs');

app.set('port', process.env.PORT || 3020);
app.set('env', process.env.NODE_ENV || 'local');

var currentRoom = {};
var currentUserName = {};

io.on('connection', function (socket) {
    var redisClient = redis.createClient();
    redisClient.subscribe('message-channel');

    redisClient.on('message', function(channel, message) {
        message = JSON.parse(message);

        if (currentRoom[socket.id] === message['chat_id']) {
            socket.json.send(message);
        }
    });

    redisClient.on('message-channel', function (channel, message) {
        message = JSON.parse(message);

        if (currentRoom[socket.id] === message['chat_id']) {
            socket.json.send(message);
        }
    });

    socket.on('join', function(data) {
        socket.join(data.chatId);
        currentRoom[socket.id] = data.chatId;
        currentUserName[socket.id] = data.userName;
    });

    socket.on('typing', function (data) {
        socket.broadcast.emit('isTyping', {
            isTyping: data,
            person: currentUserName[socket.id],
            chat: currentRoom[socket.id]
        });
    });

    socket.on('disconnect', function () {
        socket.leave(currentRoom[socket.id]);
        redisClient.quit();
    });
});

server.listen(app.get('port'), function() {
    console.log('Skills web server listening on port ' + server.address().port);
});
