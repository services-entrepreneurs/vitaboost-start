/**
 * Segenvita Workflow.
 *
 * This file adds Gulp tasks to the theme.
 *
 * @author Joseph Elsener
 */

// Require our dependencies.
const autoprefixer = require('autoprefixer');
const browserSync = require('browser-sync').create();
// const compass = require('gulp-compass');
const cleancss = require('gulp-clean-css');
const cssnano = require('gulp-cssnano');
const gulp = require('gulp');
const minify = require('gulp-minify');
const mqpacker = require('css-mqpacker');
const notify = require('gulp-notify');
const pixrem = require('gulp-pixrem');
const plumber = require('gulp-plumber');
const postcss = require('gulp-postcss');
const rename = require('gulp-rename');
const sass = require('gulp-sass');
const sourcemaps = require('gulp-sourcemaps');

// Project specific variables - CHANGE THESE
const siteName = '/vitaboost/vb2020/'; // set your siteName here
// const userName = 'josep'; // set your Mac OS userName here
const proxyName = 'http://localhost/';

var env,
    jsSources,
    sassSources,
    htmlSources,
    outputDir,
    sassStyle,
    phpSources;

/**
 * Error handling
 *
 * @function
 */
function handleErrors() {
	const args = Array.prototype.slice.call(arguments);

	notify
		.onError({
			title: 'Task Failed [<%= error.message %>]',
			message:
				'<%= error %> - See console or enable logging in the plugin.'
		})
		.apply(this, args);

	// Prevent the 'watch' task from stopping
	this.emit('end');
}

sassSources = ['sass/partials/*.scss'];
outputDir = '';
htmlSources = [outputDir + '*.html'];
phpSources = [outputDir + '*.php'];

/*************
 * CSS Tasks
 ************/

/**
 * PostCSS Task Handler
 */
gulp.task('postcss', () => {
	gulp
		.src('./sass/style.scss')

		// Error handling.
		.pipe(
			plumber({
				errorHandler: handleErrors
			})
		)

		// Wrap tasks in a sourcemap.
		.pipe(sourcemaps.init())

		// Sass magic.
		.pipe(
			sass({
				errLogToConsole: true,
				outputStyle: 'expanded' // Options: nested, expanded, compact, compressed
			})
		)

		// Pixel fallbacks for rem units.
		.pipe(pixrem())

		// PostCSS magic.
		.pipe(
			postcss([
				autoprefixer(),
				mqpacker({
					sort: true
				})
			])
		)

		// Create the source map.
		.pipe(
			sourcemaps.write('./', {
				includeContent: false
			})
		)

		// Write the CSS file.
		.pipe(gulp.dest('./'))

		// Inject CSS into Browser.
		.pipe(browserSync.stream());
});

/**
 * Compass Task Handler
 */
// gulp.task('compass', function() {
//   gulp.src(sassSources)
//     .pipe(compass({
//       sass: 'sass/partials',
//       css: outputDir,// + 'css',
//       image: outputDir + 'images',
//       style: sassStyle,
//       require: ['susy', 'breakpoint']
//     })
//     .on('error', gutil.log))
// //    .pipe(gulp.dest( outputDir + 'css'))
//     .pipe(connect.reload())
// });

/**
 * Minify style.css
 */
gulp.task('css:minify', ['postcss'], () => {
	gulp
		.src('./style.css')

		// Error handling.
		.pipe(
			plumber({
				errorHandler: handleErrors
			})
		)

		// Combine similar rules.
		.pipe(
			cleancss({
				level: {
					2: {
						all: true
					}
				}
			})
		)

		// Minify and optimize style.css.
		.pipe(
			cssnano({
				safe: false,
				discardComments: {
					removeAll: true
				}
			})
		)

		// Rename the file.
		.pipe(rename('style.min.css'))

		// Write the file.
		.pipe(gulp.dest('./'))

		// Inject the CSS into the browser.
		.pipe(browserSync.stream())

		.pipe(
			notify({
				message: 'Styles are built.'
			})
		);
});

/*******************
 * JavaScript Tasks
 *******************/

/**
 * JavaScript Task Handler.
 */
gulp.task('js', () => {
	gulp
		.src(['!./js/*.min.js', './js/*.js'])

		// Error handling.
		.pipe(
			plumber({
				errorHandler: handleErrors
			})
		)

		// Minify JavaScript.
		.pipe(
			minify({
				ext: {
					src: '.js',
					min: '.min.js'
				},
				noSource: true
			})
		)
		.pipe(gulp.dest('js'))

		// Inject changes via browserSync.
		.pipe(
			browserSync.reload({
				stream: true
			})
		)

		.pipe(
			notify({
				message: 'Scripts are minified.'
			})
		);
});

/**********************
 * All Tasks Listeners
 *********************/

/**
 * Reload browser for PHP & JS file changes and inject CSS changes.
 *
 * https://browsersync.io/docs/gulp
 */
gulp.task('watch', () => {
	browserSync.init({
		proxy: `http://${siteName}`,
		host: siteName,
		open: 'local',
		port: 3010
		// https: {
		// 	key: `/Users/${userName}/.valet/Certificates/${siteName}.key`,
		// 	cert: `/Users/${userName}/.valet/Certificates/${siteName}.crt`
		// }
	});

	// Watch Scss files. Changes are injected into the browser from within the task.
	gulp.watch('./sass/**/*.scss', ['styles'], ['compass']);

	// Watch JavaScript Files. The task tries to inject changes into the browser. If that's not possible, it reloads the browser.
	gulp.watch(['./js/*.js', '!./js/*.min.js'], ['scripts']);

	// Watch PHP files and reload the browser if there is a change. Add directories if needed.
	gulp
		.watch([
			'./*.php',
			'./lib/*.php',
			'./lib/**/*.php'
		])
		.on('change', browserSync.reload);
});

/********************
 * Individual tasks.
 *******************/
gulp.task('styles', ['css:minify']);
gulp.task('scripts', ['js']);

gulp.task('default', ['watch'], () => {
	gulp.start('styles', 'scripts');
});
