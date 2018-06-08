const gulp = require('gulp');
const sass = require('gulp-sass');
const livereload = require('gulp-livereload');
const filter = require('gulp-filter');
const sourcemaps = require('gulp-sourcemaps');

gulp.task('styles', () => {
  return gulp.src('./styles/**/*.scss')
    .pipe(sourcemaps.init())
    .pipe(sass().on('error', sass.logError))
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest('./styles'))
    .pipe(filter('**/*.css'))
    .pipe(livereload());
});

gulp.task('watch', () => {
  livereload.listen();
  gulp.watch('./styles/**/*.scss', ['styles']);
});

gulp.task('build', ['styles', 'watch']);
