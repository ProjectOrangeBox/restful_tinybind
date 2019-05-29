/**
 *
 * Rely on TinyBind being loaded
 * uses javascript router code
 * Using jQuery trigger, ajax
 *
 * https://blikblum.github.io/tinybind/
 * https://github.com/matthieuriolo/rivetsjs-stdlib
 * http://krasimirtsonev.com/blog/article/A-modern-JavaScript-router-in-100-lines-history-api-pushState-hash-url
 *
 */

/**
 * Setup the default application
 */
var app = {
	id: 'app', /* attach to this DOM selector */
	configurationURL: '/', /* default location to call for the configuration */
	config: {}, /* config options */
	local: {}, /* storage for local application variables */
	error: false, /* do we have an error - boolean true/false */
	errors: {}, /* "errors":{"robots":{"Name":"Name is required.","Year":"Year is required."}}} */
	record: {}, /* when model is single records */
	records: [], /* when model is multiple records */
	page: {}, /* page variables */
	form: {}, /* form variables */
	events: {}, /* store actual events */
	bound: undefined, /* are we attached to the DOM */
	init(configurationURL) {
		app.response[200] = function(data, textStatus, jqXHR) {
			if (data.config != undefined) {
				app.config = Object.assign(app.config, data.config);
			}

			if (data.flags.cache != undefined) {
				storage.removeOlderThan(data.flags.cache);
			}

			/* then call the router */
			app.helpers.route();
		};

		configurationURL = (configurationURL) ? configurationURL : app.configurationURL;

		app.helpers.ajax('get',configurationURL,{},app.helpers.getHandlers());
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
	response: {
		/* default responds */

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
	helpers: {
		ajax(method,url,data,handlers,dataType) {
			dataType = (dataType) || 'json';

			jQuery.ajax({
				method: method,
				url: url,
				data: data,
				dataType: dataType,
				cache: true,
				timeout: 5000, /* 5 seconds */
				async: true,
				statusCode: Object.assign(app.response,handlers),
			});
		},
		getHandlers() {
			return app.response;
		},
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
			return (app.record) ? 'object' : 'array';
		},
		getModel() {
			return model = (app.record) ? app.record : app.records;
		},
		load(layoutEndPoint,modelEndPoint) {
			/* unbind */
			jQuery('body').trigger('bound',false);

			/* load this layout then call this */
			app.helpers.loadTemplate(layoutEndPoint,function(data, textStatus, jqXHR) {
				document.getElementById(app.id).innerHTML = data.source;

				console.log(textStatus,data);

				if (modelEndPoint) {
					/* setup retrieve model - success */
					app.response[200] = function(data, textStatus, jqXHR) {
						app.helpers.setData(data);
						app.bound = tinybind.bind(document.getElementById(app.id),app);

						/* rebound */
						jQuery('body').trigger('bound',true);
					};

					app.helpers.ajax('get',modelEndPoint,{},app.helpers.getHandlers());
				}
			});
		},
		route(path) {
			path = (path) ? path : window.location.pathname;

			if (!app.router.isSetup) {
				app.router.isSetup = true;
				app.router.config({ mode:'history'}).listen();
			}

			app.router.check(path);
		},
		loadTemplate(layoutEndPoint,then) {
			/* Get bind template from browser local session storage? */
			var cachedTemplateData = storage.getItem(layoutEndPoint+'.bind');

			/* have we already loaded the template? */
			if (cachedTemplateData) {
				then(cachedTemplateData, 'cached', undefined);
			} else {
				/* setup retrieve model - success */
				app.response[200] = function(data, textStatus, jqXHR) {
					var cacheTemplateData = {source: data.template.source, cache: data.template.cache};

					storage.setItem(layoutEndPoint+'.bind',cacheTemplateData,data.template.cache);

					then(cacheTemplateData, textStatus, jqXHR);
				};

				app.helpers.ajax('get',layoutEndPoint,{},app.helpers.getHandlers());
			}
		},
	}
};

/* bootstrap once the DOM is loaded */
document.addEventListener('DOMContentLoaded',function(){
	app.init();
});