'use strict';

var gulp = require('gulp');
var less = require('gulp-less');
var browserify = require('gulp-browserify');
var concat = require('gulp-concat');
var path = require('path');
var nodemon = require('gulp-nodemon');
var assetHash = require('./gulp/asset-hash');

var paths = {
    less: ['./public/less/**/*.less'],
    browserify: ['./public/js/**/*.js', './public/js/**/*.html'],
    cssDist: ['./public/dist/**/*.css'],
    jsDist: ['./public/dist/**/*.js']
};

var jsLibs = [
    './public/bower_components/angular/angular.js',
    './public/bower_components/angular-ui-router/release/angular-ui-router.js',
    './public/bower_components/angular-bootstrap/ui-bootstrap-tpls.js',
    './public/bower_components/angular-password/angular-password.js',
    './public/bower_components/moment/moment.js',
    './public/bower_components/moment-timezone/moment-timezone.js',
    './public/bower_components/lodash/lodash.js',
    './public/bower_components/satellizer/satellizer.min.js',
    './public/bower_components/jquery/dist/jquery.min.js',
    './public/bower_components/bootstrap/dist/js/bootstrap.min.js',
    './node_modules/alertifyjs/build/alertify.js'
];

gulp.task('build', ['generate-asset-hashes', 'copy-alertify-styles']);

gulp.task('monitor', ['build'], function() {
    nodemon({
        script: 'local.js',
        ext: 'js html json',
        env: { 'NODE_ENV': 'local' },
        "watch": [
            "public/dist"
        ]
    });

    gulp.watch(paths.browserify, ['browserify']);
    gulp.watch(paths.less, ['less']);
    gulp.watch(paths.cssDist, ['monitor-asset-hashes']);
    gulp.watch(paths.jsDist, ['monitor-asset-hashes']);
});

gulp.task('less', function () {
    gulp.src('./public/less/skills.less')
        .pipe(less({
            paths: [ path.join(__dirname, 'less') ]
        }))
        .pipe(gulp.dest('./public/dist'));
});

gulp.task('browserify', function() {
    gulp.src('./public/js/skills.js')
        .pipe(browserify({
            insertGlobals : true,
            debug : true
        }))
        .pipe(gulp.dest('./public/dist'));
});

gulp.task('generate-asset-hashes', ['less', 'browserify', 'concat-js-libs'], function() {
    gulp.src(['./public/dist/lens.js', './public/dist/libs.js', './public/dist/lens.css'])
        .pipe(assetHash(
            'asset-hashes.json',
            {
                hashLength: 10
            }
        ))
        .pipe(gulp.dest('./public/dist'));
});

gulp.task('monitor-asset-hashes', function() {
    gulp.src(['./public/dist/skills.js', './public/dist/libs.js', './public/dist/phplabs.css'])
        .pipe(assetHash(
            'asset-hashes.json',
            {
                hashLength: 10
            }
        ))
        .pipe(gulp.dest('./public/dist'));
});

gulp.task('concat-js-libs', function() {
    gulp.src(jsLibs)
        .pipe(concat('libs.js'))
        .pipe(gulp.dest('./public/dist/'));
});


gulp.task('copy-alertify-styles', function () {
    return gulp.src(['./node_modules/alertifyjs/build/css/alertify.css', './node_modules/alertifyjs/build/css/themes/bootstrap.css'])
        .pipe(gulp.dest('./public/dist/alertify'));
});