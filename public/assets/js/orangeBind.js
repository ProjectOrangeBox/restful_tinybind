/**
 *
 * Rely on TinyBind being loaded
 * https://blikblum.github.io/tinybind/
 * https://github.com/matthieuriolo/rivetsjs-stdlib
 *
 * Router idea from http://krasimirtsonev.com/blog/article/A-modern-JavaScript-router-in-100-lines-history-api-pushState-hash-url
 *
 * Using jQuery trigger to notify other plugins of binding changes. see app.triggers
 *
 */

/**
 * Setup the default application
 */
var app = {
	id: 'app', /* attach to this DOM selector */
	configurationURL: '/', /* default location to call for the configuration */
	config: {
		ajaxTimeout: 5000, /* ajax timeout in seconds */
		routerRoot: '/', /* router url root */
	}, /* config options */
	local: {}, /* storage for local application variables */
	error: false, /* do we have an error - boolean true/false */
	errors: {}, /* "errors":{"robots":{"Name":"Name is required.","Year":"Year is required."}}} */
	record: {}, /* when model is single records */
	records: [], /* when model is multiple records */
	page: {}, /* page variables */
	form: {}, /* form variables */
	events: {}, /* store actual events */
	bound: undefined, /* are we attached to the DOM */
	triggers: {
		bound: function(){
			jQuery('body').trigger('bound',true);
		},
		unbound: function(){
			jQuery('body').trigger('bound',false);
		},
	},
	init(configurationURL) {
		app.response[200] = function(data, xhr) {
			if (data.config != undefined) {
				app.config = Object.assign(app.config, data.config);
			}

			if (data.flags.cache != undefined) {
				storage.removeOlderThan(data.flags.cache);
			}

			/* then call the router */
			app.helpers.route();
		};

		configurationURL = configurationURL || app.configurationURL;

		app.request('get',configurationURL);
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
		getFragment: function() {
			var fragment = '';

			fragment = this.clearSlashes(decodeURI(location.pathname + location.search));
			fragment = fragment.replace(/\?(.*)$/, '');
			fragment = app.config.routerRoot != '/' ? fragment.replace(app.config.routerRoot, '') : fragment;

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

			return this;
		},
		check: function(f) {
			/* multiple exists */
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
			path = path || '';

			history.pushState(null, null, app.config.routerRoot + this.clearSlashes(path));

			return this;
		}
	},
	response: {
		/* standard get layout or get model */
		200: function(responds, xhr){ console.log(arguments); alert('200 (ok) handler'); },
		/* success on create */
		201: function(responds, xhr){ console.log(arguments); alert('201 (created) handler'); },
		/* success on edit */
		202: function(responds, xhr){ console.log(arguments); alert('202 (accepted) handler'); },
		/* access to resource not allowed */
		401: function(responds, xhr){ console.log(arguments); alert('401 (unauthorized) handler'); },
		/* resource not found */
		404: function(responds, xhr){ console.log(arguments); alert('404 (not found) handler'); },
		/* error submitting resource (create, edit, delete) */
		406: function(responds, xhr){ console.log(arguments); alert('406 (not accepted) handler'); },
		/* resource conflict ie. trying to create a new resource with the same primary id */
		409: function(responds, xhr){ console.log(arguments); alert('409 (conflict) handler'); },
		/* internal server error */
		500: function(responds, xhr){ console.log(arguments); alert('500 (server error) handler'); },

		/* else */
		else: function(responds, xhr){ console.log(arguments); alert('else?'); },
	},
	helpers: {
		setData(data) {
			/* overwrite */
			if (data['error']) {
				app.error = data['error'];
			}

			if (data['errors']) {
				app.errors = data['errors'];
			}

			/**
			 * need to bind array and object separately
			 * because if a array is bound and you send a object it chokes
			 */
			if (data['model']) {
				if (Array.isArray(data['model'])) {
					app.records = data['model'];
					app.record = undefined;
				} else {
					app.record = data['model'];
					app.records = undefined;
				}
			}

			/* merge */
			var params = ['page','form'];

			for (var index in params) {
				var key = params[index];
				if (data[key] != undefined) {
					for (var subkey in data[key]) {
						app[key][subkey] = data[key][subkey];
					}
				}
			}
		},
		getData() {
			return {
				error: app.error,
				errors: app.errors,
				model: app.helpers.getModel(),
				page: app.page,
				form: app.form,
			};
		},
		modelIsA() {
			return app.record ? 'object' : 'array';
		},
		getModel() {
			return model = app.record || app.records;
		},
		load(layoutEndPoint,modelEndPoint) {
			/* unbind */
			app.triggers.unbound();

			/* load this layout then call this */
			app.helpers.loadTemplate(layoutEndPoint,function(data, xhr) {
				document.getElementById(app.id).innerHTML = data.source;

				if (modelEndPoint) {
					/* setup retrieve model - success */
					app.response[200] = function(data, xhr) {
						app.helpers.setData(data);
						app.bound = tinybind.bind(document.getElementById(app.id),app);

						/* rebound */
						app.triggers.bound();
					};

					app.request('get',modelEndPoint);
				}
			});
		},
		route(path) {
			path = path || window.location.pathname;

			if (!app.router.isSetup) {
				app.router.isSetup = true;
				app.router.listen();
			}

			app.router.check(path);
		},
		loadTemplate(layoutEndPoint,then) {
			var key = layoutEndPoint+'.template';

			/* Get bind template from browser local session storage? */
			var cachedTemplateData = storage.getItem(key);

			/* have we already loaded the template? */
			if (cachedTemplateData) {
				then(cachedTemplateData, 'cached', undefined);
			} else {
				/* setup retrieve model - success */
				app.response[200] = function(data, xhr) {
					storage.setItem(key,data.template,data.template.cache);

					then(data.template, xhr);
				};

				app.request('get',layoutEndPoint);
			}
		},
		urlencoded: function(obj, prefix) {
			var str = [];

			for (var p in obj) {
				if (obj.hasOwnProperty(p)) {
					var k = prefix ? prefix + "[" + p + "]" : p, v = obj[p];

					str.push(typeof v == "object" ? this.urlencoded(v, k) : encodeURIComponent(k) + "=" + encodeURIComponent(v));
				}
			}

			return str.join("&");
		},
	},
	request: function(method,url,data,handlers) {
		var xhr = new XMLHttpRequest();

		xhr.timeout = app.config.ajaxTimeout;

		xhr.open(method.toUpperCase(),url,true); /* true = async */
		xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		xhr.setRequestHeader('Accept','application/json, text/javascript, */*');
		xhr.setRequestHeader('Cache-Control','no-cache');
		xhr.setRequestHeader('X-Requested-With','XMLHttpRequest');

		/* merge any sent in with any of the defaults - used inside onHandler() */
		handlers = Object.assign(app.response,handlers);

		/* this handles everything because we setup the handlers */
		var onHandler = function() {
			/**
			 * try to convert the responds to a json object
			 * everything in orangeBind is a ajax request and json should be returned
			 */
			try {
				var object = JSON.parse(xhr.response);
			} catch(e) {
				var object = {};
			}

			/* do we have a handler for this responds? if not fall back to "else" */
			var handlerKey = handlers.hasOwnProperty(xhr.status) ? xhr.status : 'else';

			/* call the handler */
			handlers[handlerKey](object,xhr);
		}

		/* they all use the same handler? */
		xhr.onload = onHandler;
		xhr.onerror = onHandler;
		xhr.onabort = onHandler;
		xhr.ontimeout = onHandler;

		var request = data ? app.helpers.urlencoded(data) : '';

		xhr.send(request);
	}
};

/* bootstrap once the DOM is loaded */
document.addEventListener('DOMContentLoaded',function(){
	app.init();
});