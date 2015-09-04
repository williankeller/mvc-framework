/* 
 * Copyright (C) 2015 wkeller
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
var basePaths = {
    src: 'assets/',
    dest: 'assets/',
    bower: 'bower_components/'
};
var paths = {
    images: {
        src: basePaths.src + 'images/',
        dest: basePaths.dest + 'images/min/'
    },
    scripts: {
        src: basePaths.src + 'js/',
        dest: basePaths.dest + 'js/min/'
    },
    styles: {
        src: basePaths.src + 'sass/',
        dest: basePaths.dest + 'css/min/'
    },
    sprite: {
        src: basePaths.src + 'images/sprite/*'
    }
};

var appFiles = {
    styles: paths.styles.src + '**/*.scss',
    scripts: [paths.scripts.src + 'scripts.js']
};

var vendorFiles = {
    styles: '',
    scripts: ''
};

var spriteConfig = {
    imgName: 'sprite.png',
    cssName: '_sprite.scss',
    imgPath: paths.images.dest.replace('public', '') + 'sprite.png' // Gets put in the css
};

/*
 Let the magic begin
 */
var gulp = require('gulp');

var es = require('event-stream');
var gutil = require('gulp-util');

var plugins = require("gulp-load-plugins")({
    pattern: ['gulp-*', 'gulp.*'],
    replaceString: /\bgulp[\-.]/
});

// Allows gulp --dev to be run for a more verbose output
var isProduction = true;
var sassStyle = 'compressed';
var sourceMap = false;

if (gutil.env.dev === true) {
    sassStyle = 'expanded';
    sourceMap = true;
    isProduction = false;
}

var changeEvent = function (evt) {
    gutil.log('File', gutil.colors.cyan(evt.path.replace(new RegExp('/.*(?=/' + basePaths.src + ')/'), '')), 'was', gutil.colors.magenta(evt.type));
};

/*
 * Compile SASS
 * Compress CSS
 */
gulp.task('css', function () {

    var sassFiles = gulp.src(appFiles.styles)
            .pipe(plugins.cssGlobbing({
                extensions: ['.scss']
            }))
            .pipe(plugins.rubySass({
                style: sassStyle, sourcemap: sourceMap, precision: 2
            }))
            .on('error', function (err) {
                new gutil.PluginError('CSS', err, {showStack: true});
            });

    return es.concat(gulp.src(vendorFiles.styles), sassFiles)
            .pipe(plugins.concat('main.min.css'))
            .pipe(plugins.autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4', 'Firefox >= 4'))
            .pipe(isProduction ? plugins.combineMediaQueries({
                log: true
            }) : gutil.noop())
            .pipe(isProduction ? plugins.cssmin() : gutil.noop())
            .pipe(plugins.size())
            .pipe(gulp.dest(paths.styles.dest));
});

/*
 * Compress scripts
 */
gulp.task('scripts', function () {

    gulp.src(vendorFiles.scripts.concat(appFiles.scripts))
            .pipe(plugins.concat('app.min.js'))
            .pipe(isProduction ? plugins.uglify() : gutil.noop())
            .pipe(plugins.size())
            .pipe(gulp.dest(paths.scripts.dest));

});

/*
 * Sprite Generator
 */
gulp.task('sprite', function () {
    var spriteData = gulp.src(paths.sprite.src).pipe(plugins.spritesmith({
        imgName: spriteConfig.imgName,
        cssName: spriteConfig.cssName,
        imgPath: spriteConfig.imgPath,
        cssOpts: {
            functions: false
        },
        cssVarMap: function (sprite) {
            sprite.name = 'sprite-' + sprite.name;
        }
    }));
    spriteData.img.pipe(gulp.dest(paths.images.dest));
    spriteData.css.pipe(gulp.dest(paths.styles.src));
});

/*
 * Watch content changes
 */
gulp.task('watch', ['sprite', 'css', 'scripts'], function () {
    gulp.watch(appFiles.styles, ['css']).on('change', function (evt) {
        changeEvent(evt);
    });
    gulp.watch(paths.scripts.src + '*.js', ['scripts']).on('change', function (evt) {
        changeEvent(evt);
    });
    gulp.watch(paths.sprite.src, ['sprite']).on('change', function (evt) {
        changeEvent(evt);
    });
});

gulp.task('default', ['css', 'watch'/*, 'scripts'*/]);