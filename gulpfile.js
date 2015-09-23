var
  gulp = require('gulp'),
  plumber = require('gulp-plumber'), // Prevent pipe breaking caused by errors from gulp plugins.
  uglify = require('gulp-uglify'), // Minify JavaScript with UglifyJS.
  concat = require('gulp-concat'), // It concats files to a single file.
  sourcemaps = require('gulp-sourcemaps'), // Inline maps are embedded in the source file.
  jade = require('gulp-jade'),
  browserSync = require('browser-sync'),
  reload = browserSync.reload,
  sass = require('gulp-sass');

var paths = {
  ui: [
    './src/ui/init.js',
    './src/ui/lib/**/*.js',
    './src/ui/end.js'
  ],
  sass: './sass/**/*.sass',
  jade: {
    compile: './index.jade',
    watch: './**/*.jade'
  }
};

gulp.task('ui', function() {
  return gulp.src(paths.ui)
    .pipe(concat('ui.js'))
    .pipe(gulp.dest('./js/'))
    .pipe(reload({stream: true}));
});

gulp.task('compile_3pjs', function () {
  gulp.src([
      './bower_components/jquery/dist/jquery.js',
      './bower_components/underscore/underscore.js',
      './bower_components/angular/angular.js',
      './bower_components/angular-underscore-module/angular-underscore-module.js',
      './bower_components/angular-animate/angular-animate.js',
      './bower_components/angular-aria/angular-aria.js',
      './bower_components/angular-material/angular-material.js'
    ])
    .pipe(plumber())
    .pipe(concat('3p.js'))
    //  .pipe(uglify())
    .pipe(sourcemaps.write())
    .pipe(gulp.dest('./js/'))
    .pipe(reload({stream: true}));
});

gulp.task('compile_3pcss', function () {
  gulp.src([
    './bower_components/angular-material/angular-material.css'
    ])
    .pipe(plumber())
    .pipe(sourcemaps.init())
    .pipe(concat('3p.css'))
    //.pipe(uglify())
    .pipe(sourcemaps.write())
    .pipe(gulp.dest('./css/'));
});


gulp.task('sass', function () {
  gulp.src(paths.sass)
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('./css'))
    .pipe(reload({stream: true}));
});

gulp.task('jade', function() {
  var YOUR_LOCALS = {};

  gulp.src(paths.jade.compile)
    .pipe(jade({
      pretty: true,
      locals: {
        greeting: 'Hi'
      }
    }))
    .pipe(gulp.dest('.'))
    .pipe(reload({stream: true}));
});

gulp.task('serve', function () {
  browserSync({
    server: {
      baseDir: '.'
    }
  });
  gulp.watch(paths.sass, ['sass']);
  gulp.watch(paths.jade.watch, ['jade']);
  gulp.watch(paths.ui, ['ui']);
});
