const gulp = require('gulp');
const base64 = require('gulp-css-base64');
const sass = require('gulp-sass');
const autoprefixer = require('gulp-autoprefixer');
const cleanCSS = require('gulp-clean-css');
const livereload = require('gulp-livereload');
const filter = require('gulp-filter');

gulp.task('styles', () => {
  return gulp.src('./styles/**/*.scss')
    .pipe(base64())
    .pipe(sass().on('error', sass.logError))
    .pipe(autoprefixer({
      browsers: ['last 4 versions'],
      cascade: false
    }))
    .pipe(cleanCSS({ compatibility: 'ie8' }))
    .pipe(gulp.dest('./styles'))
    .pipe(filter('**/*.css'))
    .pipe(livereload());
});

gulp.task('watch', () => {
  livereload.listen();
  gulp.watch('./styles/**/*.scss', ['styles']);
});

gulp.task('build', ['styles']);
gulp.task('default', ['styles', 'watch']);
