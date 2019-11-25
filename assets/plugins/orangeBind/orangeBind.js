var orangeBinder = {
	bind: function (id, configUrl, templateUrl, modelUrl) {
		this.id = id;

		this.bound = undefined;

		this.error = false;
		this.errors = {};

		this.model = {};
		this.record = {};
		this.records = {};

		this.page = new orangeBinder.collection(this);
		this.form = new orangeBinder.collection(this);
		this.user = new orangeBinder.collection(this);
		this.local = new orangeBinder.collection(this);

		this.config = new orangeBinder.collection(this);
		this.methods = new orangeBinder.collection(this);
		this.events = new orangeBinder.collection(this);
		this.triggers = new orangeBinder.collection(this);
		this.templates = new orangeBinder.collection(this);

		this.response = new orangeBinder.response(this);
		this.request = new orangeBinder.request(this);
		this.router = new orangeBinder.router(this);

		this.setData = function (data) {
			console.debug('setData', data);

			if (typeof data.page === 'object') {
				this.page.alter(data.page);
			}

			if (typeof data.form === 'object') {
				this.form.alter(data.form);
			}

			if (typeof data.config === 'object') {
				this.config.alter(data.config);
			}

			this.error = data.error || this.error;
			this.errors = data.errors || this.errors;
			this.model = data.model || this.model;

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
		};

		this.getData = function () {
			return {
				error: this.error,
				errors: this.errors,
				model: this.model,
				page: this.page.collect(),
				form: this.form.collect(),
			};
		};

		this.cacheCleanUp = function (config) {
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
		};

		this.loadModel = function (modelEndPoint, templateEndPoint) {
			var _parent = this;

			modelEndPoint = this.config.modelUrl + modelEndPoint;

			console.debug('Model End Point ' + modelEndPoint);

			if (templateEndPoint) {
				/* load the template then the model */
				this.loadTemplate(templateEndPoint, function () {
					_parent._loadModel(modelEndPoint);
				});
			} else {
				/* just load the model */
				this._loadModel(modelEndPoint);
			}

			return this; /* allow chaining */
		};

		this.loadTemplate = function (templateEndPoint, then) {
			var _parent = this;
			var key = templateEndPoint + '.template';
			var template = undefined;

			if (this.templates[templateEndPoint] !== undefined) {
				template = this.templates[templateEndPoint];
			} else if (storage !== undefined) {
				/* Get bind template from browser local session storage? */
				template = storage.getItem(key, undefined);
			}

			/* have we already loaded the template? */
			if (template !== undefined) {
				this.getElementById().innerHTML = template;

				if (then) {
					then();
				}
			} else {
				/* setup retrieve model - success */
				this.response.alter(200, function (data, status, xhr) {
					var cacheSeconds = data.template.cache ? data.template.cache : _parent.config.templateCache;

					if (storage !== undefined) {
						storage.setItem(key, data.template.source, cacheSeconds);
					}

					_parent.getElementById().innerHTML = data.template.source;

					if (then) {
						then();
					}
				});

				var url = this.config.templateUrl + templateEndPoint;

				console.debug('Template End Point: ' + url);

				_parent.request.get(url);
			}

			return this; /* allow chaining */
		};

		this.getElementById = function () {
			var element = document.getElementById(this.id);

			if (element === null) {
				console.error('Element Id "' + this.id + '" Not Found.');
			} else {
				console.debug('Binding To "' + this.id + '"');
			}

			return element;
		};

		this.refresh = function (data, then) {
			this.triggers.unbound();

			/* unbind tinybind */
			if (this.bound) {
				this.bound.unbind();
			}

			/* update instance data */
			if (data) {
				this.setData(data);
			}

			this.bound = tinybind.bind(this.getElementById(), this);

			/* tell everyone we now have new data */
			this.triggers.bound();

			if (then) {
				then();
			}
		};

		this._loadModel = function (modelEndPoint, then) {
			var _parent = this;

			this.response.alter(200, function (data, status, xhr) {
				_parent.refresh(data, then);
			});

			/* run the query */
			_parent.request.get(modelEndPoint);

			return this; /* allow chaining */
		};

		this.domReady = function () {
			var _parent = this;

			/* default init 200 callback */
			this.response.alter(200, function (data, xhr) {
				_parent.setData(data);

				/* send into tinybind the configuration */
				tinybind.configure(_parent.config.tinyBind);

				/**
				 * Turn on the listener to check to see if the current route is something we are listening for
				 * if a match is found then trigger the callback with the url
				 * ie. callback('/foo/bar');
				 */
				_parent.router.check();
			});

			/* Make a Request for the configuration url using the default 200 responds we just setup above */
			this.request.get(this.config.configUrl);
		}

		/* finish init */
		this.config.alter({
			defaults: {},
			configUrl: (configUrl || ''),
			modelUrl: (modelUrl || ''),
			templateUrl: (templateUrl || ''),
			redirect: false,
			ajaxTimeout: 5000,
			routerRoot: '/',
			storageCache: 0,
			templateCache: 0,
			clearCache: false,
			ajaxCacheBuster: false,
			tinyBind: {
				prefix: 'rv',
				preloadData: true,
				rootInterface: '.',
				templateDelimiters: ['{', '}'],
			}
		});

		/* attach our default triggers */
		this.triggers.alter({
			bound: function () {
				jQuery('body').trigger('tiny-bind-bound');
			},
			unbound: function () {
				jQuery('body').trigger('tiny-bind-unbound');
			},
			bindNavigate: function () {
				jQuery('body').trigger('spa-navgate');
			},
		});
	},
	router: function (parent) {
		this._parent = parent;

		this.routes = [];

		/* reference to intervalID */
		this.interval = undefined;

		/* check the router url and call the callback if a match is found */
		this.check = function (url) {
			/* do we have any routes to listen for? */
			if (this.routes.length) {

				/* did they send in a url? if not then get the current url */
				url = url || this.getUrl();

				console.info('router::check', url);

				/* loop though the routes */
				for (var key in this.routes) {
					var parameters = url.match(this.routes[key].re);

					if (parameters) {
						console.info('router::check::parameters', parameters, this.routes[key].re.toString());

						/* remove matched url  */
						parameters.shift();

						/* call the route callback and pass in the parameters */
						this.routes[key].callback.apply({}, parameters);

						break; /* break from for loop */
					}
				}

				/* turn on listening if it's not already */
				if (!this.interval) {
					this.listen();
				}
			}

			return this; /* allow chaining */
		};

		/* get the current page url */
		this.getUrl = function () {
			var url = '';

			url = this._clearSlashes(decodeURI(location.pathname + location.search));
			url = url.replace(/\?(.*)$/, '');
			url = this._parent.config.routerRoot !== '/' ? url.replace(this._parent.config.routerRoot, '') : url;

			return this._clearSlashes(url);
		};

		/* add or change a route */
		this.alter = function (regularExpression, callback) {
			if (typeof regularExpression === 'object') {
				for (var property in regularExpression) {
					this.alter(property, regularExpression[property]);
				}
			} else {
				/* add to the routes array */
				this.routes.push({
					re: this._reformatRegularExpression(regularExpression),
					callback: callback
				});
			}

			return this; /* allow chaining */
		};

		/* remove a single route it's url regular expression */
		this.remove = function (regularExpression) {
			var re = this._reformatRegularExpression(regularExpression);

			for (var key in this.routes) {
				if (re.toString() == this.routes[key].re.toString()) {
					this.routes.splice(key, 1);
				}
			}

			return this; /* allow chaining */
		};

		this._reformatRegularExpression = function (regularExpression) {
			/* trim / fore & aft */
			regularExpression = this._clearSlashes(regularExpression);

			/* escape / to \/ */
			regularExpression = regularExpression.replace(
				new RegExp('/', 'g'),
				"\\/"
			);

			/* add CodeIgniter matches */
			regularExpression = regularExpression.replace(
				new RegExp(':any', 'g'),
				'[^/]+'
			); /* anything */
			regularExpression = regularExpression.replace(
				new RegExp(':num', 'g'),
				'[0-9]+'
			); /* number only */
			regularExpression = regularExpression.replace(
				new RegExp(':hex', 'g'),
				'[0-9a-f]+'
			); /* hex values */
			regularExpression = regularExpression.replace(
				new RegExp(':str', 'g'),
				'[0-9a-zA-Z]+'
			); /* str values */

			return new RegExp(regularExpression);
		};

		/* delete all routes */
		this.flush = function () {
			this.routes = [];

			return this; /* allow chaining */
		};

		/* start router listener checking for changes in the url */
		this.listen = function () {
			var _parent = this;

			/* what is the current url */
			var current = this.getUrl();

			/* Do we have any routes to listen for? */
			if (this.routes.length) {
				/* clear any current interval */
				clearInterval(this.interval);

				/* we are now listening for url changes */
				this.interval = setInterval(function () {
					if (current !== _parent.getUrl()) {
						current = _parent.getUrl();
						_parent.check(current);
					}
				}, 100);
			}

			return this; /* allow chaining */
		};

		/* navigate to a new url optionally specifying it as a redirect or history change */
		this.navigate = function (url, redirect) {
			url = url ? this._parent.config.routerRoot + this._clearSlashes(url) : '';
			redirect = redirect ? redirect : this._parent.config.redirect;

			console.info('router::navigate', url, redirect);

			/* trigger a redirect so other javascript code knows we are redirecting */
			this._parent.triggers.bindNavigate(url, redirect);

			if (redirect) {
				/* full page reload so trigger wouldn't even be picked up */
				window.location.href = url;
			} else {
				/* adds a state to the browser's session history stack redirect */
				history.pushState(null, null, url);
			}

			return this; /* allow chaining */
		};

		/* remove all slashes from the beginning and end of the passed url */
		this._clearSlashes = function (url) {
			return url
				.toString()
				.replace(/\/$/, '')
				.replace(/^\//, '');
		};
	},
	response: function (parent) {
		this._parent = parent;

		this.callbacks = {};

		this.defaultCallbacks = {
			/* standard get layout or get model */
			200: function (data, status, xhr) {
				console.debug(arguments);
				alert('200 (ok) callback');
			},
			/* success on create */
			201: function (data, status, xhr) {
				console.debug(arguments);
				alert('201 (created) callback');
			},
			/* success on edit */
			202: function (data, status, xhr) {
				console.debug(arguments);
				alert('202 (accepted) callback');
			},
			/* access to resource not allowed */
			401: function (xhr, status, error) {
				console.debug(arguments);
				alert('401 (unauthorized) callback');
			},
			/* resource not found */
			404: function (xhr, status, error) {
				console.debug(arguments);
				alert('404 (not found) callback');
			},
			/* error submitting resource (create, edit, delete) */
			406: function (xhr, status, error) {
				console.debug(arguments);
				alert('406 (not accepted) callback');
			},
			/* resource conflict ie. trying to create a new resource with the same primary id */
			409: function (xhr, status, error) {
				console.debug(arguments);
				alert('409 (conflict) callback');
			},
			/* internal server error */
			500: function (xhr, status, error) {
				console.debug(arguments);
				alert('500 (server error) callback');
			}
		};

		this.alter = function (code, callback) {
			if (typeof code === 'object') {
				for (var property in code) {
					this.alter(property, code[property]);
				}
			} else if (Number.isInteger(code) && typeof callback === 'function') {
				/* change the responds callback based on the returned http status code */
				this.callbacks[code] = callback;
			}

			return this;
		};

		this.callbacks = this.defaultCallbacks;
	},
	request: function (parent) {
		this._parent = parent;

		/* any method */
		this.send = function (method, url, data, callbacks) {
			console.info('request::send', method, url, data);

			jQuery.ajax({
				method: method,
				url: url,
				data: data,
				dataType: 'json',
				cache: !this._parent.config.ajaxCacheBuster,
				/* ajax cache buster? */
				async: true,
				/* always! */
				timeout: this._parent.config.ajaxTimeout,
				/* 5 seconds */
				statusCode: this._parent.response.alter(callbacks).callbacks
			});

			return this;
		};

		/* REST / HTTP - get */
		this.get = function (url, data, callbacks) {
			return this.send('get', url, data, callbacks);
		};

		/* REST / HTTP  - post */
		this.post = function (url, data, callbacks) {
			return this.send('post', url, data, callbacks);
		};

		/* REST / HTTP  - patch */
		this.patch = function (url, data, callbacks) {
			return this.send('patch', url, data, callbacks);
		};

		/* CRUD / SQL / REST / HTTP  - delete */
		this.delete = function (url, data, callbacks) {
			return this.send('delete', url, data, callbacks);
		};

		/* CRUD - create */
		this.create = function (url, data, callbacks) {
			return this.send('post', url, data, callbacks);
		};

		/* CRUD - read */
		this.read = function (url, data, callbacks) {
			return this.send('get', url, data, callbacks);
		};

		/* CRUD / SQL - update */
		this.update = function (url, data, callbacks) {
			return this.send('patch', url, data, callbacks);
		};

		/* SQL - insert */
		this.insert = function (url, data, callbacks) {
			return this.send('post', url, data, callbacks);
		};
	},
	collection: function (parent) {
		this._parent = parent;

		this.alter = function (name, value) {
			if (typeof name === 'object') {
				for (var property in name) {
					this[property] = name[property];
				}
			} else {
				this[name] = value;
			}

			return this;
		};

		this.collect = function () {
			var collection = {};

			for (var propertyName in this) {
				if (typeof this[propertyName] !== 'function' && propertyName !== '_parent') {
					collection[propertyName] = this[propertyName];
				}
			}

			return collection;
		};
	}
};