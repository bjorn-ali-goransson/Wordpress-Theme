/// <vs BeforeBuild='default' />

var gulp = require('gulp');
var del = require('del');
var _ = require('underscore');

var iis = require('gulp-iis-express');

gulp.task('iis', function(){
  return iis({sitePaths:['MyWebsiteNameInApplicationHost.config']});
});


gulp.task('clean', function(){
  return del('vendor/**/*');
});

var extensions = ['js', 'css', 'less', 'map', 'eot', 'woff', 'woff2', 'ttf', 'svg', 'otf', 'gif', 'png'];

gulp.task('deps', ['clean'], function () {
  return gulp
  .src(
      _
      .map(extensions, function (extension) {
          return 'bower_components/**/*.' + extension;
      })
      .concat([
          '!**/Gruntfile.js',
          '!**/package.js',
          '!**/grunt.js',
          '!**/grunt/**/*',
          '!**/src/**/*'
      ])
  )
  .pipe(gulp.dest('vendor'));
});

var less = require('gulp-less');
var watch = require('gulp-watch');
var prefix = require('gulp-autoprefixer');
var livereload = require('gulp-livereload');
var rename = require('gulp-rename');
var path = require('path');

gulp.task('less', wrapPipe(function(success, error) {
    return gulp.src('./styles/*.less')
        .pipe(rename(function (path) {
          path.basename += ".less";
        }))
        .pipe(
          less({
            globalVars: { 'themeurl': '\'/wp-content/themes/' + path.basename(__dirname) + '\'' }

          })
          .on('error', error)
        )
        .pipe(prefix("last 8 version", "> 1%", "ie 8", "ie 7"), {cascade:true})
        .pipe(gulp.dest('./styles/compiled/'))
        .pipe(livereload());
}));
gulp.task('watch', function() {
    livereload.listen();
    return gulp.watch('./**/*.less', ['less']);  // Watch all the .less files, then run the less task
});



/* WRAP PIPE */

function wrapPipe(taskFn) { // https://gist.github.com/just-boris/89ee7c1829e87e2db04c
  return function(done) {
    var onSuccess = function() {
      done();
    };
    var onError = function(err) {
      done(err);
    }
    var outStream = taskFn(onSuccess, onError);
    if(outStream && typeof outStream.on === 'function') {
      outStream.on('end', onSuccess);
    }
  }
}
