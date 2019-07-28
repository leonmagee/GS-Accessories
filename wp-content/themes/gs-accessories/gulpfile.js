/**
 *  Initialize Gulp
 */
const gulp = require('gulp');

/**
 *  Load Gulp Dependencies
 */
const sass = require('gulp-sass');
const minifycss = require('gulp-minify-css');
const rename = require('gulp-rename');
const util = require('gulp-util');
const browserSync = require('browser-sync').create();
const autoprefixer = require('gulp-autoprefixer');

gulp.task('scss', function() {
  gulp
    .src(['assets/scss/import.scss'])
    .pipe(
      sass({
        style: 'compressed',
        errLogToConsole: true,
        includePaths: ['node_modules/motion-ui/src'],
      })
    )
    .pipe(rename('main.min.css'))
    .pipe(autoprefixer())
    .pipe(minifycss())
    .pipe(gulp.dest('assets/css'))
    .pipe(browserSync.stream());
  util.log(util.colors.red('Compiled!'));
});

gulp.task('scss-admin', function() {
  gulp
    .src(['assets/scss_admin/admin.scss'])
    .pipe(
      sass({
        style: 'compressed',
        errLogToConsole: true,
        includePaths: ['node_modules/motion-ui/src'],
      })
    )
    .pipe(rename('admin.min.css'))
    .pipe(autoprefixer())
    .pipe(minifycss())
    .pipe(gulp.dest('assets/css'))
    .pipe(browserSync.stream());
  util.log(util.colors.red('Compiled!'));
});

gulp.task('default', ['scss', 'scss-admin', 'watch', 'browser-sync']);

gulp.task('browser-sync', function() {
  browserSync.init({
    proxy: 'https://www.gs-accessories.dev', // this proxys my dev site to localhost:3000
    open: false,
    port: 1115,
    https: {
      key: '/Users/leonmagee/.localhost-ssl/key.pem',
      cert: '/Users/leonmagee/.localhost-ssl/cert.pem',
    },
  });
});

gulp.task('watch', function() {
  /**
   *  Watch PHP files for changes
   */
  const php = '**/*.php';

  gulp.watch(php).on('change', function(file) {
    gulp.src(php).pipe(browserSync.stream());

    util.log(util.colors.blue(`[ ${file.path} ]`));
  });

  const js = 'assets/js/**/*.js';

  gulp.watch(js).on('change', function(file) {
    gulp.src(js).pipe(browserSync.stream());

    util.log(util.colors.blue(`[ ${file.path} ]`));
  });

  /**
   *  Watch SCSS files for changes - trigger 'scss' task
   */
  gulp.watch('assets/scss/**/*.scss', ['scss']);
  gulp.watch('assets/scss_admin/**/*.scss', ['scss-admin']);
});
