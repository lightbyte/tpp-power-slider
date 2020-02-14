// Defining requirements
var gulp = require('gulp');
var plumber = require('gulp-plumber');
var sass = require('gulp-sass');
var babel = require('gulp-babel');
var postcss = require('gulp-postcss');
var watch = require('gulp-watch');
var touch = require('gulp-touch-fd');
var rename = require('gulp-rename');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var imagemin = require('gulp-imagemin');
var ignore = require('gulp-ignore');
var rimraf = require('gulp-rimraf');
var sourcemaps = require('gulp-sourcemaps');
var del = require('del');
var cleanCSS = require('gulp-clean-css');
var gulpSequence = require('gulp-sequence');
var replace = require('gulp-replace');
var autoprefixer = require('autoprefixer');

// Configuration file to keep your code DRY
var cfg = require('./gulpconfig.json');
var paths = cfg.paths;

// Run:
// gulp sass
// Compiles SCSS files in CSS
gulp.task('sass', function () {
	var stream = gulp
		.src(paths.sass + '/styles.scss')
		.pipe(
			plumber({
				errorHandler: function (err) {
					console.log(err);
					this.emit('end');
				}
			})
		)
		.pipe(sourcemaps.init({ loadMaps: true }))
		.pipe(sass({ errLogToConsole: true }))
		.pipe(postcss([autoprefixer()]))
		.pipe(sourcemaps.write(undefined, { sourceRoot: null }))
		.pipe(gulp.dest(paths.css))
		.pipe(touch());
	return stream;
});

// Run:
// gulp watch
// Starts watcher. Watcher runs gulp sass task on changes
gulp.task('watch', function () {
	gulp.watch([`${paths.sass}/**/*.scss`, `${paths.sass}/*.scss`], gulp.series('styles'));
	gulp.watch(
		[
			`${paths.dev}/js/**/*.js`
		],
		gulp.series('scripts')
	);

});

// Run:
// gulp cssnano
// Minifies CSS files
gulp.task('cssnano', function () {
	return gulp
		.src(paths.css + '/styles.css')
		.pipe(sourcemaps.init({ loadMaps: true }))
		.pipe(
			plumber({
				errorHandler: function (err) {
					console.log(err);
					this.emit('end');
				}
			})
		)
		.pipe(rename({ suffix: '.min' }))
		.pipe(cssnano({ discardComments: { removeAll: true } }))
		.pipe(sourcemaps.write('./'))
		.pipe(gulp.dest(paths.css))
		.pipe(touch());
});

gulp.task('minifycss', function () {

	return gulp
		.src([
			`${paths.css}/styles.css`,
		])
		.pipe(sourcemaps.init({
			loadMaps: true
		}))
		.pipe(cleanCSS({
			compatibility: '*'
		}))
		.pipe(
			plumber({
				errorHandler: function (err) {
					console.log(err);
					this.emit('end');
				}
			})
		)
		.pipe(rename({ suffix: '.min' }))
		.pipe(sourcemaps.write('./'))
		.pipe(gulp.dest(paths.css))
		.pipe(touch());
});

gulp.task('cleancss', function () {
	return gulp
		.src(`${paths.css}/*.min.css`, { read: false }) // Much faster
		.pipe(rimraf());
});

gulp.task('styles', function (callback) {
	gulp.series('sass', 'minifycss')(callback);
});


// Run:
// gulp scripts.
// Uglifies and concat all JS files into one
gulp.task('scripts', function () {
	var scripts = [
		`${paths.dev}/js/slick.min.js`,
		`${paths.dev}/js/script.js`
	];
	gulp
		.src(scripts, { allowEmpty: true })
		.pipe(babel(
			{
			presets: ['@babel/preset-env']
			}
		))
		.pipe(concat('script.min.js'))
		.pipe(uglify())
		.pipe(gulp.dest(paths.js));

	return gulp
		.src(scripts, { allowEmpty: true })
		.pipe(babel())
		.pipe(concat('script.js'))
		.pipe(gulp.dest(paths.js));
});

// Deleting any file inside the /src folder
gulp.task('clean-source', function () {
	return del(['src/**/*']);
});

// Run:
// gulp copy-assets.
// Copy all needed dependency assets files from bower_component assets to themes /js, /scss and /fonts folder. Run this task after bower install or bower update

////////////////// All Bootstrap SASS  Assets /////////////////////////
gulp.task('copy-assets', function (done) {
	// Copy all JS files
	var stream = gulp
		.src(`${paths.node}slick-carousel/slick/slick.min.js`)
		.pipe(gulp.dest(`${paths.dev}/js/`));

	gulp
		.src(`${paths.node}slick-carousel/slick/slick.scss`)
		.pipe(gulp.dest(`${paths.dev}/sass/`));

	gulp
		.src(`${paths.node}slick-carousel/slick/slick-theme.scss`)
		.pipe(gulp.dest(`${paths.dev}/sass/`));

	gulp
		.src(`${paths.node}slick-carousel/slick/fonts/*`)
		.pipe(gulp.dest(`${paths.css}/fonts/`));


	done();
});

// Deleting the files distributed by the copy-assets task
gulp.task('clean-vendor-assets', function () {
	return del([
		`${paths.dev}/js/slick.min.js`,
		`${paths.dev}/sass/slick.scss`,
		`${paths.dev}/sass/slick-theme.scss`,
		paths.vendor !== '' ? paths.js + paths.vendor + '/**' : ''
	]);
});

// Deleting any file inside the /dist folder
gulp.task('clean-dist', function () {
	return del([paths.dist + '/**']);
});

// Run
// gulp dist
// Copies the files to the /dist folder for distribution as simple theme
gulp.task(
	'dist',
	gulp.series(['clean-dist'], function () {
		return gulp
			.src(
				[
					'**/*',
					`!${paths.bower}`,
					`!${paths.bower}/**`,
					`!${paths.node}`,
					`!${paths.node}/**`,
					`!${paths.dev}`,
					`!${paths.dev}/**`,
					`!${paths.dist}`,
					`!${paths.dist}/**`,
					`!${paths.distprod}`,
					`!${paths.distprod}/**`,
					`!${paths.sass}`,
					`!${paths.sass}/**`,
					'!readme.txt',
					'!readme.md',
					'!package.json',
					'!package-lock.json',
					'!gulpfile.js',
					'!gulpconfig.json',
					'!CHANGELOG.md',
					'!.travis.yml',
					'!jshintignore',
					'!codesniffer.ruleset.xml',
					'*'
				],
				{ buffer: true }
			)
			.pipe(gulp.dest(paths.dist))
			.pipe(touch());
	})
);

// Deleting any file inside the /dist-product folder
gulp.task('clean-dist-product', function () {
	return del([paths.distprod + '/**']);
});

// Run
// gulp dist-product
// Copies the files to the /dist-prod folder for distribution as theme with all assets
gulp.task(
	'dist-product',
	gulp.series(['clean-dist-product'], function () {
		return gulp
			.src([
				'**/*',
				`!${paths.bower}`,
				`!${paths.bower}/**`,
				`!${paths.node}`,
				`!${paths.node}/**`,
				`!${paths.dist}`,
				`!${paths.dist}/**`,
				`!${paths.distprod}`,
				`!${paths.distprod}/**`,
				'*'
			])
			.pipe(gulp.dest(paths.distprod))
			.pipe(touch());
	})
);

// Run
// gulp compile
// Compiles the styles and scripts and runs the dist task
gulp.task('compile', gulp.series('styles', 'scripts', 'dist'));

// Run:
// gulp
// Starts watcher (default task)
gulp.task('default', gulp.series('watch'));
