'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');
var uglifycss = require('gulp-uglifycss');
var wait = require('gulp-wait');

gulp.task('sass', function () {
	return gulp.src('./resources/sass/*.scss')
		.pipe(wait(200))
		.pipe(sass().on('error', sass.logError))
		.pipe(gulp.dest('./public/css'));
});

gulp.task('minify-css', function () {
	return gulp.src('./public/css/*.css')
		.pipe(uglifycss({
			"maxLineLen": 200,
			"uglyComments": false
		}))
	.pipe(gulp.dest('./public/css/'));
});

gulp.task('css', gulp.series('sass', (done) => {
	gulp.watch("./resources/sass/*.scss", gulp.series('sass'));
	done();
}));

gulp.task('default', gulp.series('css'));
