'use strict';

var fs = require('fs');
var path = require('path');
var through = require('through2');
var crypto = require('crypto');
var gulpUtil = require('gulp-util');
var File = gulpUtil.File;

module.exports = function (file, options) {
    var hashes = {};

    var bufferHashes = function (file, enc, callback) {
        var fileName = path.basename(file.path);
        hashes[fileName] = crypto.createHash('md5')
            .update(fs.readFileSync(file.path))
            .digest('hex')
            .slice(options.hashLength ? -options.hashLength : -10);

        return callback();
    };

    var endStream = function(callback) {
        var targetFile = new File({
            path: file
        });
        targetFile.contents = new Buffer(JSON.stringify(hashes));

        this.push(targetFile);

        return callback;
    };

    return through.obj(bufferHashes, endStream);
};