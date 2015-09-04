/// <vs BeforeBuild='default' />
var gulp = require('gulp');
var del = require('del');
var _ = require('underscore');

var extensions = ['js', 'css', 'less', 'map', 'eot', 'woff', 'woff2', 'ttf', 'svg', 'otf'];

gulp.task('deps', function () {
    del('UI/Vendor/**/*', function () {
        gulp
        .src(
            _
            .map(extensions, function (extension) {
                return 'bower_components/**/*.' + extension;
            })
            .concat([
                '!**/Gruntfile.js',
                '!**/grunt/**/*',
                '!**/src/**/*'
            ]),
            {}
        )
        .pipe(gulp.dest('vendor'));
    });
});

var less = require('gulp-less');
var watch = require('gulp-watch');
var prefix = require('gulp-autoprefixer');
var plumber = require('gulp-plumber');
var livereload = require('gulp-livereload');
var rename = require('gulp-rename');
var path = require('path');

gulp.task('less', function() {
    return gulp.src('./styles/*.less')  // only compile the entry file
        .pipe(plumber())
        .pipe(rename(function (path) {
          path.basename += ".less";
        }))
        .pipe(less({
          globalVars: { 'themeurl': '\'/wp-content/themes/' + path.basename(__dirname) + '\'' }
        }))
        .pipe(prefix("last 8 version", "> 1%", "ie 8", "ie 7"), {cascade:true})
        .pipe(gulp.dest('./styles/'))
        .pipe(livereload());
});
gulp.task('watch', function() {
    gulp.watch('./**/*.less', ['less']);  // Watch all the .less files, then run the less task
});

