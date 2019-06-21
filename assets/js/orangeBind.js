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
		templateCache: 0,
		clearCache: false,
		ajaxCacheBuster: false,
		tinyBind: {
			prefix: 'rv',
			preloadData: true,
			rootInterface: '.',
			templateDelimiters: ['{','}'],
		},
	},
	bound: undefined, /* are we attached to the DOM */
	local: {}, /* storage for local application variables */
	error: false, /* do we have an error - boolean true/false */
	errors: {}, /* "errors":{"robots":{"Name":"Name is required.","Year":"Year is required."}}} */
	model: {}, /* actual model storage */
	record: {}, /* when model is single records */
	records: [], /* when model is multiple records */
	page: {}, /* page variables */
	form: {}, /* form variables */
	events: {}, /* store actual events */
	triggers: {
		bound: function(){
			jQuery('body').trigger('tiny-bind-bound');
		},
		unbound: function(){
			jQuery('body').trigger('tiny-bind-unbound');
		},
	},
	init: function(){
		/* save a external scope reference */
		var parent = this;

		/* default init 200 handler */
		this.response.change(200,function(data, xhr) {
			parent.setData(data);

			tinybind.configure(parent.config.tinyBind);

			parent.router.check();
		});

		this.request.get(this.config.url);
	},
	event: {
		/* wrapper to add events like this.event.add('name',function(){}); */
		add: function(name,handler) {
			app.events[name] = handler;

			return this; /* allow chaining */
		}
	},
	router: {
		routes: [],
		interval: undefined,
		listening: undefined,
		check: function(url) {
			/* Do we have any routes to listen for? */
			if (this.routes.length) {
				/* turn on listening */
				url = url || this.getUrl();

				console.info('router::check',url);

				/* are we listening for changes? if not start listener */
				if (!this.listening) {
					this.listening = this.listen();
				}

				for (var i = 0; i < this.routes.length; i++) {
					var match = url.match(this.routes[i].re);

					if (match) {
						match.shift();

						console.info('router::check::match',this.routes[i].re.toString());

						/* call the route callback */
						this.routes[i].callback.apply({}, match);

						break; /* break from for loop */
					}
				}
			}

			return this; /* allow chaining */
		},
		getUrl: function() {
			var url = '';

			url = this._clearSlashes(decodeURI(location.pathname + location.search));
			url = url.replace(/\?(.*)$/, '');
			url = app.config.routerRoot !== '/' ? url.replace(app.config.routerRoot, '') : url;

			return this._clearSlashes(url);
		},
		add: function(regularExpression, callback) {
			/* handle the default when a callback is sent in for the regular expression */
			if (typeof regularExpression === 'function') {
				callback = regularExpression;
				regularExpression = '';
			}

			/* trim / fore & aft */
			regularExpression = this._clearSlashes(regularExpression);

			/* escape / to \/ */
			regularExpression = regularExpression.replace(new RegExp('/',"g"),'\\/');

			/* add CodeIgniter matches */
			regularExpression = regularExpression.replace(new RegExp(":any","g"),'[^/]+'); /* anything */
			regularExpression = regularExpression.replace(new RegExp(":num","g"),'[0-9]+'); /* number only */
			regularExpression = regularExpression.replace(new RegExp(":hex","g"),'[0-9a-f]+'); /* hex values */
			regularExpression = regularExpression.replace(new RegExp(":str","g"),'[0-9a-zA-Z]+'); /* str values */

			/* add to the routes array */
			this.routes.push({ re: new RegExp(regularExpression), callback: callback});

			return this; /* allow chaining */
		},
		remove: function(param) {
			var parent = this;

			this.routes.forEach(function(value,index) {
				if (value.callback === param || value.re.toString() === param.toString()) {
					parent.routes.splice(index, 1);
				}
			});

			return this; /* allow chaining */
		},
		flush: function() {
			this.routes = [];

			return this; /* allow chaining */
		},
		listen: function() {
			var parent = this;
			var current = this.getUrl();

			/* Do we have any routes to listen for? */
			if (this.routes.length) {
				clearInterval(this.interval);

				/* we are now listening for url changes */
				this.interval = setInterval(function() {
					if (current !== parent.getUrl()) {
						current = parent.getUrl();
						parent.check(current);
					}
				}, 50);
			}

			return this; /* allow chaining */
		},
		navigate: function(url) {
			url = (url) ? app.config.routerRoot + this._clearSlashes(url) : '';

			console.info('router::navigate',url);

			history.pushState(null, null, url);

			return this; /* allow chaining */
		},
		_clearSlashes: function(url) {
			return url.toString().replace(/\/$/, '').replace(/^\//, '');
		},
	},
	response: {
		_handlers: {
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
		change: function(code,handler) {
			/* change the responds handler based on the returned http status code */
			this._handlers[code] = handler;

			return this;
		},
		add: function(code,handler) {
			/* wrapper */
			return this.change(code,handler);
		},
		handlers: function(handlers) {
			/* get the handlers */
			return jQuery.extend(this._handlers,handlers);
		}
	},
	request: {
		/* any method */
		send: function(method,url,data,handlers) {
			console.info('request::send',method,url,data);

			jQuery.ajax({
				method: method,
				url: url,
				data: data,
				dataType: 'json',
				cache: !app.config.ajaxCacheBuster, /* ajax cache buster? */
				async: true, /* always! */
				timeout: app.config.ajaxTimeout, /* 5 seconds */
				statusCode: app.response.handlers(handlers),
			});

			return this;
		},
		/* REST / HTTP - get */
		get: function(url,data,handlers) {
			return this.send('get',url,data,handlers);
		},
		/* REST / HTTP  - post */
		post: function(url,data,handlers) {
			return this.send('post',url,data,handlers);
		},
		/* REST / HTTP  - patch */
		patch: function(url,data,handlers) {
			return this.send('patch',url,data,handlers);
		},
		/* CRUD / SQL / REST / HTTP  - delete */
		delete: function(url,data,handlers) {
			return this.send('delete',url,data,handlers);
		},
		/* CRUD - create */
		create: function(url,data,handlers) {
			return this.send('post',url,data,handlers);
		},
		/* CRUD - read */
		read: function(url,data,handlers) {
			return this.send('get',url,data,handlers);
		},
		/* CRUD / SQL - update */
		update: function(url,data,handlers) {
			return this.send('patch',url,data,handlers);
		},
		/* SQL - insert */
		insert: function(url,data,handlers) {
			return this.send('post',url,data,handlers);
		},
	},
	setData: function(data) {
		var parent = this;

		console.info('setData',data);

		/* overwrite */
		['error','errors','model'].forEach(function(element) {
			if (data[element]) {
				parent[element] = data[element];
			}
		});

		/* deep merge these values */
		['page','form','config'].forEach(function(element) {
			if (data[element]) {
				parent[element] = jQuery.extend(true,parent[element],data[element]);
			}
		});

		/**
		 * these are references to the actual model
		 * so the view can use records or record
		 * or you can still use model
		 */
		this.records = this.model;
		this.record = this.model;

		/* do any cache cleaning based on the sent in data */
		this.cacheCleanUp(this.config);

		return this; /* allow chaining */
	},
	getData: function() {
		return {
			error: this.error,
			errors: this.errors,
			model: this.model,
			page: this.page,
			form: this.form,
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

		return this; /* allow chaining */
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

		return this; /* allow chaining */
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
			this.response.change(200,function(data,status,xhr) {
				var cacheSeconds = (data.template.cache) ? data.template.cache : parent.config.templateCache;

				storage.setItem(key,data.template.source,cacheSeconds);

				document.getElementById(parent.id).innerHTML = data.template.source;

				if (then) {
					then();
				}
			});

			parent.request.get(templateEndPoint);
		}

		return this; /* allow chaining */
	},
	/* actual model load */
	_loadModel: function(modelEndPoint,then) {
		var parent = this;

		this.response.change(200,function(data,status,xhr) {
			/* tell everyone we are unbinding the data */
			parent.triggers.unbound();

			/* unbind tinybind */
			if (parent.bound) {
				parent.bound.unbind();
			}

			/* update app data */
			parent.setData(data);

			/* rebind */
			parent.bound = tinybind.bind(document.getElementById(parent.id),app);

			/* tell everyone we now have new data */
			parent.triggers.bound();

			if (then) {
				then();
			}
		});

		/* run the query */
		parent.request.get(modelEndPoint);

		return this; /* allow chaining */
	},
};