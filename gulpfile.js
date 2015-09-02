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


'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var autoprefixer = require('gulp-autoprefixer');
var livereload = require('gulp-livereload');
var gulpCssGlobbing = require("gulp-css-globbing");
var imagemin = require('gulp-imagemin');
var spritesmith = require('gulp.spritesmith');
var cssmin = require('gulp-cssmin');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');

gulp.task('sass:prod', function () {
    gulp.src('./assets/sass/*.scss')
            .pipe(sass().on('error', sass.logError))
            .pipe(autoprefixer({
                browsers: ['last 2 version']
            }))
            .pipe(gulp.dest('./assets/css'));
});

gulp.task('sass:dev', function () {
    gulp.src('./assets/sass/*.scss')
            .pipe(gulpCssGlobbing({
                extensions: ['.scss']
            }))
            .pipe(sourcemaps.init())
            .pipe(sass({includePaths: [
                    'node_modules/breakpoint-sass/stylesheets/'
                ]}).on('error', sass.logError))
            .pipe(autoprefixer({
                browsers: ['last 2 version']
            }))
            .pipe(sourcemaps.write('.'))
            .pipe(gulp.dest('./assets/css'));
});

gulp.task('sprite', function () {
    var spriteData = gulp.src('./assets/images/**/*.png').pipe(spritesmith({
        imgName: 'sprite.png',
        cssName: './assets/sass/variables/_sprite.scss'
    }));

    spriteData.img
            .pipe(imagemin())
            .pipe(gulp.dest('./assets/images/sprite/'));

    spriteData.css
            .pipe(gulp.dest('./assets/sass/variables/'));

});

gulp.task('css:compress', function () {
    gulp.src('./assets/css/**/*.css')
            .pipe(cssmin())
            .pipe(rename({suffix: '.min'}))
            .pipe(gulp.dest('./assets/css'));
});

gulp.task('js:compress', function () {
    gulp.src('./assets/javascript/*.js')
            .pipe(rename({suffix: '.min'}))
            .pipe(uglify())
            .pipe(gulp.dest('./assets/javascript/'));
});

gulp.task('sass:watch', function () {
    gulp.watch('./assets/sass/**/*.scss', ['sass:dev']);

    livereload.listen();

    gulp.watch(['./assets/css/**/*']).on('change', livereload.changed);
});

gulp.task('default', ['sass:dev', 'sass:watch', /*'sprite',*/ 'css:compress', 'js:compress']);
