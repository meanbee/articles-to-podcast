// Get modules
var gulp = require('gulp'),
    imagemin = require('gulp-imagemin'),
    plumber = require('gulp-plumber'),
    minifyCSS = require('gulp-minify-css'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),
    sass = require('gulp-sass');

// Task Styles
gulp.task('styles', function () {
    gulp.src('sass/styles.scss')
        .pipe(plumber())
        .pipe(sass())
        .pipe(minifyCSS({keepBreaks:true}))
        .pipe(gulp.dest('css'));
});

// Task scripts
gulp.task('scripts', function() {
    gulp.src(['js/src/lib/*.js'])
        .pipe(plumber())
        .pipe(concat('main.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('js'))
});

// Optimise images
gulp.task('images', function () {
    gulp.src('images-orig/**/*.{png,gif,jpg,ico,svg}')
        .pipe(plumber())
        .pipe(imagemin())
        .pipe(gulp.dest('images/'));
});

gulp.task('watch', function () {
    gulp.watch('sass/**/*.scss', ['styles']);
    gulp.watch('js/src/**/*.js', ['scripts']);
    gulp.watch('images-orig/**', ['images']);
});

// The default task (called when you run `gulp` from cli)
gulp.task('default', ['watch']);
