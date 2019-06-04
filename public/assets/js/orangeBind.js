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
	config: { /* config options */
		url: '/',
		ajaxTimeout: 5000, /* ajax timeout in seconds */
		routerRoot: '/', /* router url root */
		storageCache: 2592000, /* about 1 month */
		templateCache: 0, /* flush after */
		clearCache: false,
	},
	modelIsA: undefined,
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
	init() {
		/* save a external scope reference */
		var parent = this;

		/* default init 200 handler */
		this.response[200] = function(data, xhr) {
			parent.setData(data).route();
		};

		this.request('get',this.config.url);
	},
	event: {
		/* wrapper to add events like this.event.add('name',function(){}); */
		add(name,handler) {
			app.events[name] = handler;
			return this;
		}
	},
	route: function(path) {
		this.router.run(path);

		return this;
	},
	router: {
		routes: [],
		isSetup: false,
		run: function(path) {
			path = path || window.location.pathname;

			if (!this.isSetup) {
				this.isSetup = true;
				this.listen();
			}

			this.check(path);

			return this;
		},
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
		jQuery.ajax({
			method: method,
			url: url,
			data: data,
			dataType: 'json',
			cache: false,
			async: true,
			timeout: this.config.ajaxTimeout, /* 5 seconds */
			statusCode: jQuery.extend(this.response,handlers),
		});
	},
	setData: function(data) {
		console.info(data);

		/* overwrite */
		if (data.error) {
			this.error = data.error;
		}

		if (data.errors) {
			this.errors = data.errors;
		}

		/**
		 * overwrite
		 * need to bind array and object separately
		 * because if a array is bound and you send a object it chokes
		 */
		if (data.model) {
			if (Array.isArray(data.model)) {
				this.record = undefined;
				this.records = data.model;
				this.modelIsA = 'array';
			} else {
				this.record = data.model;
				this.records = undefined;
				this.modelIsA = 'object';
			}
		}

		/* deep merge these values */
		if (data.page) {
			this.page = jQuery.extend(true,this.page,data.page);
		}

		if (data.form) {
			this.form = jQuery.extend(true,this.form,data.form);
		}

		if (data.config) {
			this.config = jQuery.extend(true,this.config,data.config);
		}

		this.cacheCleanUp(this.config);

		return this;
	},
	getData: function() {
		return {
			error: this.error,
			errors: this.errors,
			model: this.getModel(),
			page: this.page,
			form: thisform,
		};
	},
	cacheCleanUp: function(config) {
		/* handle some caching cleanups */

		/* if true flush all */
		if (config.clearCache) {
			storage.clear();
		}

		/* if older than cache seconds... */
		if (config.olderThanCache !== undefined) {
			storage.removeOlderThan(config.olderThanCache);
		}
	},
	modelIsA: function() {
		return this.modelIsA;
	},
	getModel: function() {
		return (this.modelIsA == 'object') ? this.record : this.records;
	},
	/* load just a model or a template then a model */
	loadModel: function(modelEndPoint,templateEndPoint) {
		var parent = this;

		if (templateEndPoint) {
			/* load the template then the model */
			this.loadTemplate(templateEndPoint,function(){
				parent._loadModel(modelEndPoint);
			});
		} else {
			/* just load the model */
			this._loadModel(modelEndPoint);
		}

		return this;
	},
	loadTemplate: function(templateEndPoint,then) {
		var parent = this;
		var key = templateEndPoint+'.template';

		/* Get bind template from browser local session storage? */
		var template = storage.getItem(key,undefined);

		/* have we already loaded the template? */
		if (template) {
			document.getElementById(this.id).innerHTML = template;

			if (then) {
				then();
			}
		} else {
			/* setup retrieve model - success */
			this.response[200] = function(data,status,xhr) {
				var cacheSeconds = (data.template.cache) ? data.template.cache : parent.config.templateCache;

				storage.setItem(key,data.template.source,cacheSeconds);

				document.getElementById(parent.id).innerHTML = data.template.source;

				if (then) {
					then();
				}
			};

			parent.request('get',templateEndPoint);
		}

		return this;
	},
	/* actual model load */
	_loadModel: function(modelEndPoint,then) {
		var parent = this;

		this.response[200] = function(data,status,xhr) {
			/* tell everyone we are unbinding the data */
			parent.triggers.unbound();

			/* update app data */
			parent.setData(data);

			/* rebind */
			parent.bound = tinybind.bind(document.getElementById(parent.id),app);

			/* tell everyone we now have new data */
			parent.triggers.bound();

			if (then) {
				then();
			}
		};

		/* run the query */
		parent.request('get',modelEndPoint);

		return this;
	},
};

/* bootstrap once the DOM is loaded */
document.addEventListener('DOMContentLoaded',function(){
	app.init();
});