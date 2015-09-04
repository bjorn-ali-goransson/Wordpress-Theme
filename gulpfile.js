/// <vs BeforeBuild='default' />
var gulp = require('gulp');
var del = require('del');
var _ = require('underscore');

var extensions = ['js', 'css', 'less', 'map', 'eot', 'woff', 'woff2', 'ttf', 'svg', 'otf'];

gulp.task('default', function () {
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
