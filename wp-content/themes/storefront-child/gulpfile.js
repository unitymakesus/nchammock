"use strict";
var gulp = require('gulp');
var sass = require('gulp-sass');
var sassGlob = require('gulp-sass-glob');
var sourcemaps = require('gulp-sourcemaps');
var autoprefixer = require('gulp-autoprefixer');
var browserSync = require('browser-sync').create();
var reload = browserSync.reload;
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var watch = require('gulp-watch');

gulp.task('browser-sync', function() {
  browserSync.init({
    proxy: 'nchammock.test'
  })
});

gulp.task('sass', function () {
  return gulp.src('assets/sass/**/*.scss')
    .pipe(sassGlob())
    .pipe(sourcemaps.init())
    .pipe(autoprefixer({ browsers: ['last 2 versions'], cascade: false }))
    .pipe(sass({ outputStyle:'compressed'}).on('error', sass.logError))
    .pipe(sourcemaps.write('./maps'))
    .pipe(gulp.dest('./'));
});

gulp.task('watch', function() {
    gulp.watch('assets/sass/*.scss', ['sass']).on("change", browserSync.reload);
    gulp.watch('assets/sass/**/*.scss', ['sass']).on("change", browserSync.reload);
    gulp.watch('assets/js/**/*.js', ['js']).on("change", browserSync.reload);
});

var jsInput = { js: 'assets/js/dev/**/*.js' }
var jsOutput = 'assets/js/dist/';

gulp.task('js', function(){
  return gulp.src(jsInput.js)
    .pipe(concat('app.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest('./assets/js/dist/'))
});

gulp.task('default',['sass', 'browser-sync','watch', 'js']);
