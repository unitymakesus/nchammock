// Require gulp packages
var gulp = require('gulp');
var minifyCss = require('gulp-minify-css');
var concat = require('gulp-concat');
var sass = require('gulp-sass');
var browsersync = require('browser-sync');
var reload = browsersync.reload;

// Compile and minify Sass
gulp.task('sass', function () {
  return gulp.src('./sass/*.scss')
    .pipe(sass().on('error', sass.logError))
      .pipe(minifyCss({
          keepSpecialComments: 1
      }))
    .pipe(concat('style.css'))
    .pipe(gulp.dest('./'))
    // .pipe(browsersync.stream());
    .pipe(reload({stream:true}));
});

// Set up Browsersync
gulp.task('browser-sync', function() {
  browsersync.init({
  proxy: 'nchammock.test'
  });
});

gulp.task('reload', function () {
  browsersync.reload();
});

// Set up Watchers
gulp.task('watch', function() {
  gulp.watch('./sass/*.scss', ['sass']);
  gulp.watch(['*.html', '*.php'], ['reload']);
});

// Default Gulp tasks
gulp.task('default', ['sass', 'browser-sync', 'watch' ]);
