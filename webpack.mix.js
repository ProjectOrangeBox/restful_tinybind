let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application, as well as bundling up your JS files.
 |
 */

mix.setPublicPath('public/dist/');


let javascript = {
	bundle: [
		'node_modules/jquery/dist/jquery.js',
		'node_modules/bootstrap3/dist/js/bootstrap.js',
		'node_modules/bootstrap-select/dist/js/bootstrap-select.js',

		'node_modules/tinysort/src/tinysort.js',

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
	]
};

let css = {
	bundle: [
		'node_modules/font-awesome/css/font-awesome.css',
		'node_modules/bootstrap3/dist/css/bootstrap.css',
		'node_modules/bootstrap3/dist/css/bootstrap-theme.css',
		'node_modules/bootstrap-select/dist/css/bootstrap-select.css',
		'node_modules/roboto-fontface/css/roboto/roboto-fontface.css',

		'assets/plugins/notify/notify.css',
		'assets/plugins/table_sort/table_sort.css',
		'assets/css/.application.css'
	]
};

// mix.copy(from, to);
let copyFile = {
};

// mix.copyDirectory(fromDir, toDir);
let copyDir = {
	'node_modules/font-awesome/fonts': 'public/fonts',
	'node_modules/roboto-fontface/fonts/roboto': 'public/fonts/roboto',
};


for (var idx in javascript) {
	mix.combine(javascript[idx],`public/dist/${idx}.js`);
}

mix.sass('assets/css/application.scss','../../assets/css/.application.css');

for (var idx in css) {
	mix.combine(css[idx],`public/dist/${idx}.css`);
}

for (var idx in copyFile) {
	mix.copy(idx,copyFile[idx]);
};

for (var idx in copyDir) {
	mix.copyDirectory(idx,copyDir[idx]);
};


// Full API
// mix.js(src, output);
// mix.react(src, output); <-- Identical to mix.js(), but registers React Babel compilation.
// mix.preact(src, output); <-- Identical to mix.js(), but registers Preact compilation.
// mix.coffee(src, output); <-- Identical to mix.js(), but registers CoffeeScript compilation.
// mix.ts(src, output); <-- TypeScript support. Requires tsconfig.json to exist in the same folder as webpack.mix.js
// mix.extract(vendorLibs);
// mix.sass(src, output);
// mix.less(src, output);
// mix.stylus(src, output);
// mix.postCss(src, output, [require('postcss-some-plugin')()]);
// mix.browserSync('my-site.test');
// mix.combine(files, destination);
// mix.babel(files, destination); <-- Identical to mix.combine(), but also includes Babel compilation.
// mix.copy(from, to);
// mix.copyDirectory(fromDir, toDir);
// mix.minify(file);
// mix.sourceMaps(); // Enable sourcemaps
// mix.version(); // Enable versioning.
// mix.disableNotifications();
// mix.setPublicPath('path/to/public');
// mix.setResourceRoot('prefix/for/resource/locators');
// mix.autoload({}); <-- Will be passed to Webpack's ProvidePlugin.
// mix.webpackConfig({}); <-- Override webpack.config.js, without editing the file directly.
// mix.babelConfig({}); <-- Merge extra Babel configuration (plugins, etc.) with Mix's default.
// mix.then(function () {}) <-- Will be triggered each time Webpack finishes building.
// mix.dump(); <-- Dump the generated webpack config object to the console.
// mix.extend(name, handler) <-- Extend Mix's API with your own components.
// mix.options({
//   extractVueStyles: false, // Extract .vue component styling to file, rather than inline.
//   globalVueStyles: file, // Variables file to be imported in every component.
//   processCssUrls: true, // Process/optimize relative stylesheet url()'s. Set to false, if you don't want them touched.
//   purifyCss: false, // Remove unused CSS selectors.
//   terser: {}, // Terser-specific options. https://github.com/webpack-contrib/terser-webpack-plugin#options
//   postCss: [] // Post-CSS options: https://github.com/postcss/postcss/blob/master/docs/plugins.md
// });
