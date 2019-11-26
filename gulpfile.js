const {
	series,
	parallel,
	src,
	dest,
	watch
} = require("gulp");
const sass = require("gulp-sass");
const uglify = require("gulp-uglify");
const babel = require("gulp-babel");
const pug = require("gulp-pug");
const concat = require("gulp-concat");
const cleanCSS = require("gulp-clean-css");
const sourcemaps = require("gulp-sourcemaps");
const del = require("del");

/* attach the sass compiler to the sass class */
sass.compiler = require("node-sass");

const tempFolder = "var/gulp";
const distFolder = "public/dist";

let pugViews = ["application/views/**/*.pug"];

let css = {
	vendor: [
		"node_modules/font-awesome/css/font-awesome.css",
		"node_modules/bootstrap3/dist/css/bootstrap.css",
		"node_modules/bootstrap-select/dist/css/bootstrap-select.css",
		"node_modules/roboto-fontface/css/roboto/roboto-fontface.css"
	],
	user: [
		"assets/plugins/notify/notify.css",
		"assets/plugins/table_sort/table_sort.css"
	],
	scss: ["assets/css/application.scss"]
};

let js = {
	vendor: [
		"node_modules/jquery/dist/jquery.min.js",
		"node_modules/tinybind/dist/tinybind.min.js",
		"node_modules/bootstrap3/dist/js/bootstrap.min.js",
		"node_modules/bootstrap-select/dist/js/bootstrap-select.min.js",
		"node_modules/tinysort/dist/tinysort.min.js",
		"node_modules/bootbox/dist/bootbox.all.min.js"
	],
	user: [
		"assets/plugins/orangeBind/superStorage.js",
		/* 'assets/plugins/orangeBind/tinybind.js',*/
		"assets/plugins/orangeBind/sprintf.js",
		"assets/plugins/orangeBind/orangeBind.js",

		"assets/plugins/notify/notify.js",
		"assets/plugins/bound-table-search/bound-table-search.js",
		"assets/plugins/table_remember_position/table_remember_position.js",
		"assets/plugins/table_sort/table_sort.js",
		"assets/plugins/table_sticky_header/jquery.stickytableheaders.js",

		"assets/js/application.js",
		"assets/js/binders.js",
		"assets/js/formatters.js",

		"assets/app/app.js",
		"assets/app/nav.js",

		"assets/js/onReady.js"
	]
};

let copyDir = {
	"node_modules/font-awesome/fonts/*": "public/fonts",
	"node_modules/roboto-fontface/fonts/roboto/*": "public/fonts/roboto",
	"assets/fav/favicon.ico": "public"
};

/* all config finished */

/* auto build the watch arrays */
let watchFiles = Array.prototype.concat(
	pugViews,
	css.user,
	css.vendor,
	css.scss,
	js.vendor,
	js.user
);
let watchFilesJs = Array.prototype.concat(js.user);
let watchFilesCss = Array.prototype.concat(css.user, css.vendor, css.scss);
let watchFilesPug = pugViews;

var tasks = {
	compilePug: function () {
		return src(pugViews)
			.pipe(
				pug({
					pretty: true
				})
			)
			.pipe(dest("application/views"));
	},
	compileJsVendor: function () {
		return src(js.vendor)
			.pipe(concat("1_vendor.js"))
			.pipe(dest(tempFolder));
	},
	compileJsUser: function () {
		/*
		.pipe(sourcemaps.init())
		.pipe(sourcemaps.write('.'))
		*/

		return src(js.user)
			.pipe(babel())
			/*.pipe(uglify())*/
			.pipe(concat("2_user.js"))
			.pipe(dest(tempFolder));
	},
	combinedJs: function () {
		return src(tempFolder + "/*.js")
			.pipe(concat("bundle.js"))
			.pipe(dest(distFolder));
	},
	compileSass: function () {
		return src(css.scss)
			.pipe(sass())
			.pipe(concat("2_sass.css"))
			.pipe(dest(tempFolder));
	},
	compileCss: function () {
		return src(css.vendor)
			.pipe(src(css.user))
			.pipe(concat("1_css.css"))
			.pipe(dest(tempFolder));
	},
	combinedCss: function () {
		return src(tempFolder + "/*.css")
			.pipe(
				cleanCSS({
					compatibility: "ie9"
				})
			)
			.pipe(concat("bundle.css"))
			.pipe(dest(distFolder));
	},
	copyDirectories: function () {
		for (let idx in copyDir) {
			var callback = src(idx).pipe(dest(copyDir[idx]));
		}

		return callback;
	},
	cleanUp: function (cb) {
		return del([tempFolder + "/*", distFolder + "/*"], cb);
	}
};

exports.watch = () => {
	exports.default();
	watch(
		watchFiles,
		parallel(
			series(tasks.compileJsUser, tasks.combinedJs),
			series(tasks.compileSass, tasks.compileCss, tasks.combinedCss),
			tasks.compilePug
		)
	);
};

exports["watch:js"] = () => {
	exports.default();
	watch(watchFilesJs, series(tasks.compileJsUser, tasks.combinedJs));
};

exports["watch:css"] = () => {
	exports.default();
	watch(
		watchFilesCss,
		series(parallel(tasks.compileSass, tasks.compileCss), tasks.combinedCss)
	);
};

exports["watch:pug"] = () => {
	exports.default();
	watch(watchFilesPug, parallel(tasks.compilePug));
};

exports.clean = series(tasks.cleanUp);

exports.default = series(
	tasks.cleanUp,
	parallel(
		tasks.copyDirectories,
		series(
			parallel(tasks.compileJsVendor, tasks.compileJsUser),
			tasks.combinedJs
		),
		series(parallel(tasks.compileSass, tasks.compileCss), tasks.combinedCss),
		tasks.compilePug
	)
);