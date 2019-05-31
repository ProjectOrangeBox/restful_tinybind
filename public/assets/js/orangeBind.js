/**
 *
 * Relies on TinyBind
 * https://blikblum.github.io/tinybind/
 * https://github.com/matthieuriolo/rivetsjs-stdlib
 *
 * Relies on jQuery for Ajax calls and events
 *
 * Router idea from:
 * http://krasimirtsonev.com/blog/article/A-modern-JavaScript-router-in-100-lines-history-api-pushState-hash-url
 *
 */

/**
 * Setup the default application
 */
var app = {
	id: 'app', /* attach to this DOM selector */
	configurationURL: '/', /* default location to call for the configuration */
	config: { /* config options */
		ajaxTimeout: 5000, /* ajax timeout in seconds */
		routerRoot: '/', /* router url root */
	},
	_flags: {}, /* configuration/server flags */
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
			jQuery('body').trigger('tiny-bind-bound');
		},
		unbound: function(){
			jQuery('body').trigger('tiny-bind-unbound');
		},
	},
	init(configurationURL) {
		var parent = this;

		this.response[200] = function(data, xhr) {
			if (typeof data.config === 'object' && data.config !== null) {
				parent.config = Object.assign(parent.config, data.config);
			}

			if (typeof data.flags === 'object' && data.flags !== null) {
				parent._flags = Object.assign(parent._flags, data.flags);
			}

			if (parent.readFlag('cache') !== undefined) {
				storage.removeOlderThan(parent.readFlag('cache'));
			}

			/* then call the router */
			parent.helpers.route();
		};

		configurationURL = configurationURL || this.configurationURL;

		this.request('get',configurationURL);
	},
	readFlag: function(name) {
		return this._flags[name];
	},
	event: {
		/* wrapper to add events like this.event.add('name',function(){}); */
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
		200: function(data,status,xhr){ console.log(arguments); alert('200 (ok) handler'); },
		/* success on create */
		201: function(data,status,xhr){ console.log(arguments); alert('201 (created) handler'); },
		/* success on edit */
		202: function(data,status,xhr){ console.log(arguments); alert('202 (accepted) handler'); },
		/* access to resource not allowed */
		401: function(xhr,status,error){ console.log(arguments); alert('401 (unauthorized) handler'); },
		/* resource not found */
		404: function(xhr,status,error){ console.log(arguments); alert('404 (not found) handler'); },
		/* error submitting resource (create, edit, delete) */
		406: function(xhr,status,error){ console.log(arguments); alert('406 (not accepted) handler'); },
		/* resource conflict ie. trying to create a new resource with the same primary id */
		409: function(xhr,status,error){ console.log(arguments); alert('409 (conflict) handler'); },
		/* internal server error */
		500: function(xhr,status,error){ console.log(arguments); alert('500 (server error) handler'); },
	},
	request: function(method,url,data,handlers) {
		handlers = handlers ? Object.assign(this.response,handlers) : this.response;

		jQuery.ajax({
			method: method,
			url: url,
			data: data,
			dataType: 'json',
			cache: true,
			async: true,
			timeout: this.config.ajaxTimeout, /* 5 seconds */
			statusCode: handlers,
		});
	},
	helpers: {
		modelIsA: undefined,
		setData: function(data) {
			/* overwrite */
			if (data.error) {
				app.error = data.error;
			}

			if (data.errors) {
				app.errors = data.errors;
			}

			/**
			 * need to bind array and object separately
			 * because if a array is bound and you send a object it chokes
			 */
			if (data.model) {
				if (Array.isArray(data.model)) {
					app.record = undefined;
					app.records = data.model;
					this.modelIsA = 'array';
				} else {
					app.record = data.model;
					app.records = undefined;
					this.modelIsA = 'object';
				}
			}

			/* merge these values */
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
		getData: function() {
			return {
				error: app.error,
				errors: app.errors,
				model: app.helpers.getModel(),
				page: app.page,
				form: app.form,
			};
		},
		modelIsA: function() {
			return this.modelIsA;
		},
		getModel: function() {
			return (this.modelIsA == 'object') ? app.record : app.records;
		},
		load: function(layoutEndPoint,modelEndPoint) {
			/* unbind */
			app.triggers.unbound();

			/* load this layout then call this */
			app.helpers.loadTemplate(layoutEndPoint,function(data, xhr) {
				document.getElementById(app.id).innerHTML = data.source;

				if (modelEndPoint) {
					/* setup retrieve model - success */
					app.response[200] = function(data,status,xhr) {
						app.helpers.setData(data);
						app.bound = tinybind.bind(document.getElementById(app.id),app);

						/* rebound */
						app.triggers.bound();
					};

					app.request('get',modelEndPoint);
				} else {
					app.triggers.bound();
				}
			});
		},
		route: function(path) {
			path = path || window.location.pathname;

			if (!app.router.isSetup) {
				app.router.isSetup = true;
				app.router.listen();
			}

			app.router.check(path);
		},
		loadTemplate: function(layoutEndPoint,then) {
			var key = layoutEndPoint+'.template';

			/* Get bind template from browser local session storage? */
			var cachedTemplateData = storage.getItem(key);

			/* have we already loaded the template? */
			if (cachedTemplateData) {
				then(cachedTemplateData, 'cached', undefined);
			} else {
				/* setup retrieve model - success */
				app.response[200] = function(data,status,xhr) {
					storage.setItem(key,data.template,data.template.cache);

					then(data.template, xhr);
				};

				app.request('get',layoutEndPoint);
			}
		},
	},
};

/* bootstrap once the DOM is loaded */
document.addEventListener('DOMContentLoaded',function(){
	app.init();
});