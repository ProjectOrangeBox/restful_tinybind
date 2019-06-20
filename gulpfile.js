const { series, parallel, src, dest, watch } = require('gulp');
const sass = require('gulp-sass');
const uglify = require('gulp-uglify');
const babel = require('gulp-babel');
const pug = require('gulp-pug');
const concat = require('gulp-concat');
const cleanCSS = require('gulp-clean-css');
const copy = require('recursive-copy');
const del = require('delete');
const sourcemaps = require('gulp-sourcemaps');
const rename = require('gulp-rename');

/* attach the sass compiler to the sass class */
sass.compiler = require('node-sass');

const tempFolder = 'var/gulp';
const distFolder = 'public/dist';

let pugViews = [
	'application/views/**/*.pug',
];

let css = {
	'vendor': [
		'node_modules/font-awesome/css/font-awesome.css',
		'node_modules/bootstrap3/dist/css/bootstrap.css',
		'node_modules/bootstrap3/dist/css/bootstrap-theme.css',
		'node_modules/bootstrap-select/dist/css/bootstrap-select.css',
		'node_modules/roboto-fontface/css/roboto/roboto-fontface.css',
	],
	'user': [
		'assets/plugins/notify/notify.css',
		'assets/plugins/table_sort/table_sort.css',
	],
	'scss': [
		'assets/css/application.scss'
	],
};

let js = {
	'vendor': [
		'node_modules/jquery/dist/jquery.min.js',
		'node_modules/bootstrap3/dist/js/bootstrap.min.js',
		'node_modules/bootstrap-select/dist/js/bootstrap-select.min.js',
		'node_modules/tinysort/dist/tinysort.min.js',
	],
	'user': [
		'assets/js/superStorage.js',
		'assets/js/tinybind.js',
		'assets/js/sprintf.js',
		'assets/js/application.js',
		'assets/js/config.js',
		'assets/js/binders.js',
		'assets/js/formatters.js',
		'assets/js/orangeBind.js',

		'assets/plugins/notify/notify.js',
		'assets/plugins/bound-table-search/bound-table-search.js',
		'assets/plugins/table_remember_position/table_remember_position.js',
		'assets/plugins/table_sort/table_sort.js',
		'assets/plugins/table_sticky_header/jquery.stickytableheaders.js',

		'assets/app/app.js',

		'assets/js/onReady.js',
	],
};

let copyDir = {
	'node_modules/font-awesome/fonts': 'public/fonts',
	'node_modules/roboto-fontface/fonts/roboto': 'public/fonts/roboto',
};

/* all config finished */

/* auto build the watch arrays */
let watchFiles = Array.prototype.concat(pugViews,css.user,css.vendor,css.scss,js.vendor,js.user);
let watchFilesJs = Array.prototype.concat(js.user);
let watchFilesCss = Array.prototype.concat(css.user,css.vendor,css.scss);
let watchFilesPug = pugViews;

function compilePug() {
	return src(pugViews)
		.pipe(pug({pretty:false}))
		.pipe(dest('application/views'));
}

function compileJsVendor() {
	return src(js.vendor)
		.pipe(concat('1_vendor.js'))
		.pipe(dest(tempFolder));
}

function compileJsUser() {
	/*
	.pipe(sourcemaps.init())
	.pipe(sourcemaps.write('.'))
	*/

	return src(js.user)
		.pipe(babel())
		.pipe(uglify())
		.pipe(concat('2_user.js'))
		.pipe(dest(tempFolder));
}

function combinedJs() {
	return src(tempFolder + '/*.js')
		.pipe(concat('bundle.js'))
		.pipe(dest(distFolder));
}

function compileSass() {
	return src(css.scss)
		.pipe(sass())
		.pipe(concat('2_sass.css'))
		.pipe(dest(tempFolder));
}

function compileCss() {
	return src(css.vendor)
		.pipe(src(css.user))
		.pipe(concat('1_css.css'))
		.pipe(dest(tempFolder));
}

function combinedCss() {
	return src(tempFolder + '/*.css')
 		.pipe(cleanCSS({compatibility: 'ie9'}))
		.pipe(concat('bundle.css'))
		.pipe(dest(distFolder));
}

function copyDirectories(cb) {
	/* as long as the others finish after this... */
	for (var idx in copyDir) {
		copy(__dirname + '/' + idx,__dirname + '/' + copyDir[idx],function(error, results) {});
	};

	return cb();
}

exports.watch = ()=>{
	exports.default();
	watch(watchFiles,parallel(series(compileJsUser,combinedJs),series(compileSass,compileCss,combinedCss),compilePug));
}

exports['watch.js'] = ()=>{
	exports.default();
	watch(watchFilesJs,series(compileJsUser,combinedJs));
}

exports['watch.css'] = ()=>{
	exports.default();
	watch(watchFilesCss,series(parallel(compileSass,compileCss),combinedCss));
}

exports['watch:pug'] = ()=>{
	exports.default();
	watch(watchFilesPug,parallel(compilePug));
}

exports.default = parallel(
	copyDirectories,
	series(parallel(compileJsVendor,compileJsUser),combinedJs),
	series(parallel(compileSass,compileCss),combinedCss),
	compilePug,
);
