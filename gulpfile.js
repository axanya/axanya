var gulp = require('gulp');
var elixir = require('laravel-elixir');
var uglify = require('gulp-uglify');
var uglifycss = require('gulp-uglifycss');

elixir.extend('compress', function() {
  gulp.task('compress', function() {
  	//Front End
    gulp.src('js/*.js').pipe(uglify()).pipe(gulp.dest('min_js'));
    gulp.src('js/i18n/*.js').pipe(uglify()).pipe(gulp.dest('min_js/i18n'));
    gulp.src('css/*.css').pipe(uglifycss()).pipe(gulp.dest('min_css'));
    gulp.src('css/slider/*.css').pipe(uglifycss()).pipe(gulp.dest('min_css/slider'));
    //Back End
    gulp.src('admin_assets/dist/js/*.js').pipe(uglify()).pipe(gulp.dest('admin_assets/dist/min_js'));
    gulp.src('admin_assets/dist/css/*.css').pipe(uglifycss()).pipe(gulp.dest('admin_assets/dist/min_css'));
    gulp.src('admin_assets/dist/css/skins/*.css').pipe(uglifycss()).pipe(gulp.dest('admin_assets/dist/min_css/skins'));
  });
  return this.queueTask('compress');
});

elixir(function(mix) {
    mix.compress();
});