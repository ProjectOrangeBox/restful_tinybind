/**
 *
 * https://blikblum.github.io/tinybind/
 * https://github.com/matthieuriolo/rivetsjs-stdlib
 * http://krasimirtsonev.com/blog/article/A-modern-JavaScript-router-in-100-lines-history-api-pushState-hash-url
*/

/**
 * Setup the default application
 */
var app = {
	id: '#app',
	local: {}, /* storage for local application variables */
	error: false, /* do we have an error - boolean true/false */
	errors: {}, /* "errors":{"robots":{"Name":"Name is required.","Year":"Year is required."}}} */
	model: {}, /* main bound model */
	page: {}, /* page variables */
	form: {}, /* form variables */
	events: {}, /* store actual events */
	_templates: [], /* template cache */
	bound: undefined, /* are we attached to the DOM */
	init() {
		/* default just call the router */
		app.helpers.route();
	},
	event: {
		/* wrapper to add events like app.event.add('name',function(){}); */
		add(name,handler) {
			app.events[name] = handler;
			return this;
		}
	},
	router: {
		routes: [],
		mode: null,
		root: '/',
		config: function(options) {
			this.mode = options && options.mode && options.mode == 'history' && !!(history.pushState) ? 'history' : 'hash';
			this.root = options && options.root ? '/' + this.clearSlashes(options.root) + '/' : '/';
			return this;
		},
		getFragment: function() {
			var fragment = '';
			if(this.mode === 'history') {
				fragment = this.clearSlashes(decodeURI(location.pathname + location.search));
				fragment = fragment.replace(/\?(.*)$/, '');
				fragment = this.root != '/' ? fragment.replace(this.root, '') : fragment;
			} else {
				var match = window.location.href.match(/#(.*)$/);
				fragment = match ? match[1] : '';
			}
			return this.clearSlashes(fragment);
		},
		clearSlashes: function(path) {
			return path.toString().replace(/\/$/, '').replace(/^\//, '');
		},
		add: function(re, handler) {
			if (typeof re == 'function') {
				handler = re;
				re = '';
			}
			this.routes.push({ re: re, handler: handler});
			return this;
		},
		remove: function(param) {
			for (var i=0, r; i<this.routes.length, r = this.routes[i]; i++) {
				if (r.handler === param || r.re.toString() === param.toString()) {
					this.routes.splice(i, 1);
					return this;
				}
			}
			return this;
		},
		flush: function() {
			this.routes = [];
			this.mode = null;
			this.root = '/';
			return this;
		},
		check: function(f) {
			var fragment = f || this.getFragment();
			for (var i=0; i<this.routes.length; i++) {
				var match = fragment.match(this.routes[i].re);
				if (match) {
					match.shift();
					this.routes[i].handler.apply({}, match);
					return this;
				}
			}
			return this;
		},
		listen: function() {
			var self = this;
			var current = self.getFragment();
			var fn = function() {
				if (current !== self.getFragment()) {
					current = self.getFragment();
					self.check(current);
				}
			}
			clearInterval(this.interval);
			this.interval = setInterval(fn, 50);
			return this;
		},
		navigate: function(path) {
			path = path ? path : '';
			if (this.mode === 'history') {
				history.pushState(null, null, this.root + this.clearSlashes(path));
			} else {
				window.location.href = window.location.href.replace(/#(.*)$/, '') + '#' + path;
			}
			return this;
		}
	},
	helpers: {
		/* dummy respond handlers */
		response: {
			/* standard get layout or get model */
			200: function(data, textStatus, jqXHR){ console.log(arguments); alert('200 (ok) handler'); },
			/* success on create */
			201: function(data, textStatus, jqXHR){ console.log(arguments); alert('201 (created) handler'); },
			/* success on edit */
			202: function(data, textStatus, jqXHR){ console.log(arguments); alert('202 (accepted) handler'); },
			/* access to resource not allowed */
			401: function(jqXHR, textStatus, errorThrown){ console.log(arguments); alert('401 (unauthorized) handler'); },
			/* resource not found */
			404: function(jqXHR, textStatus, errorThrown){ console.log(arguments); alert('404 (not found) handler'); },
			/* error submitting resource (create, edit, delete) */
			406: function(jqXHR, textStatus, errorThrown){ console.log(arguments); alert('406 (not accepted) handler'); },
			/* resource conflict ie. trying to create a new resource with the same primary id */
			409: function(jqXHR, textStatus, errorThrown){ console.log(arguments); alert('409 (conflict) handler'); },
			/* internal server error */
			500: function(jqXHR, textStatus, errorThrown){ console.log(arguments); alert('500 (server error) handler'); },
		},
		ajax(method,url,data,handlers) {
			jQuery.ajax({
				method: method,
				url: url,
				data: data,
				dataType: 'json',
				cache: false,
				timeout: 5000, /* 5 seconds */
				async: true,
				statusCode: Object.assign(app.helpers.response,handlers),
			});
		},
		getHandlers() {
			return app.helpers.response;
		},
		setData(data) {
			/* overwrite */
			var params = ['error','errors','model'];

			for (var index in params) {
				if (data[params[index]] != undefined) {
					app[params[index]] = data[params[index]];
				}
			}

			/* merge */
			var params = ['page','form'];

			for (var index in params) {
				if (data[params[index]] != undefined) {
					for (key2 in data[params[index]]) {
						app[params[index]][key2] = data[params[index]][key2];
					}
				}
			}
		},
		getData() {
			return {
				error: app.error,
				errors: app.errors,
				model: app.model,
				page: app.page,
				form: app.form,
			};
		},
		load(layoutEndPoint,modelEndPoint) {
			/* unbind */
			jQuery('body').trigger('bound',false);

			/* ok */
			app.helpers.response[200] = function(data, textStatus, jqXHR) {
				app.helpers.setData(data);
				app.bound = tinybind.bind(document.querySelector(app.id),app);

				/* rebound */
				jQuery('body').trigger('bound',true);
			};

			/* have we already loaded the template? */
			if (app._templates[layoutEndPoint]) {
				/* we already cached the template so just bind */
				jQuery(app.id).html(app._templates[layoutEndPoint]);

				/* run ajax model grab which calls app.helpers.response[200] setup above */
				app.helpers.ajax('get',modelEndPoint,{},app.helpers.getHandlers());
			} else {
				/* get the template & on success bind */

				jQuery.get(layoutEndPoint,function(data) {
					/* cache it */
					app._templates[layoutEndPoint] = data;

					/* add it to the app */
					jQuery(app.id).html(app._templates[layoutEndPoint]);

					/* run ajax model grab which calls app.helpers.response[200] setup above */
					app.helpers.ajax('get',modelEndPoint,{},app.helpers.getHandlers());
				});
			}
		},
		route(path) {
			path = (path) ? path : window.location.pathname;

			if (!app.router.isSetup) {
				app.router.isSetup = true;
				app.router.config({ mode:'history'}).listen();
			}

			app.router.check(path);
		},
	}
};

/* bootstrap */
document.addEventListener('DOMContentLoaded',function(){
	app.init();
});