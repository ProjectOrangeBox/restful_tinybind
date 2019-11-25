var orangeBinder = {
	/**
	 * create the bind element
	 * arguments:
	 * id - DOM element id ie. <div id="foobar"></div>
	 * configuration url - url requested to get the "base" configuration
	 * template url - template url prefix
	 * model url - model url prefix
	 */
	bind: function (id, configUrl, templateUrl, modelUrl) {
		/* DOM element id */
		this.id = id;

		/* is tiny bound bound to anything? */
		this.bound = undefined;

		/**
		 * do we have an error - boolean true/false
		 * keep these exposed on app so tinybind can use them as a boolean
		 */
		this.error = false;
		/**
		 * "errors":{"robots":{"Name":"Name is required.","Year":"Year is required."}}}
		 * keep these exposed on app so tinybind can use them as a object
		 */
		this.errors = {};

		/**
		 * actual model storage
		 */
		this.model = {};

		/**
		 * when model is single records
		 */
		this.record = {};

		/**
		 * when model is multiple records
		 */
		this.records = [];

		/**
		 * collections - alter & collect
		 */
		this.page = new orangeBinder.collection(this);
		this.form = new orangeBinder.collection(this);
		this.user = new orangeBinder.collection(this);
		this.local = new orangeBinder.collection(this);

		this.config = new orangeBinder.collection(this);
		this.methods = new orangeBinder.collection(this);
		this.events = new orangeBinder.collection(this);
		this.triggers = new orangeBinder.collection(this);
		this.templates = new orangeBinder.collection(this);

		/**
		 * special instances
		 */
		this.response = new orangeBinder.response(this);
		this.request = new orangeBinder.request(this);
		this.router = new orangeBinder.router(this);

		/**
		 * set up the default configuration
		 */
		this.config.alter({
			settable: ['page', 'form', 'user', 'local', 'config', 'templates', 'error', 'errors', 'model'],
			gettable: ['error', 'errors', 'model', 'page', 'form'],
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

		/**
		 * merge and replace data
		 */
		this.setData = function (data, settable) {
			settable = settable || this.config.settable;

			console.log('setData', data, settable);

			for (var index in settable) {
				var key = settable[index];

				if (data[key] !== undefined) {
					console.log(key, data[key]);

					/*
					if they have alter then send them in as objects and let alter merge the contents
					else they replace the entire variable
					*/
					if (typeof this[key].alter === 'function') {
						this[key].alter(data[key]);
					} else {
						this[key] = data[key];
					}
				}
			}

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

		/**
		 * get the data about this element
		 */
		this.getData = function (gettable) {
			gettable = gettable || this.config.gettable;

			console.log('getData', gettable);

			var collection = {};

			for (var index in gettable) {
				var key = gettable[index];

				collection[key] = (typeof this[key].collect === 'function') ? this[key].collect() : this[key];
			}

			console.log(collection);

			return collection;
		};

		/**
		 * preform garage collection based on the configuration sent in (usally from the server configuration value)
		 * This uses my superStorage library to cache to the local browser application storage
		 */
		this.cacheCleanUp = function (config) {
			/* if true flush all */
			if (storage !== undefined) {
				/* set clear everything */
				if (config.clearCache) {
					storage.clear();
				}

				/* if set clear older than X seconds... */
				if (config.olderThanCache !== undefined) {
					storage.removeOlderThan(config.olderThanCache);
				}
			}

			return this; /* allow chaining */
		};

		this.loadModel = function (modelEndPoint, templateEndPoint) {
			var _parent = this;

			modelEndPoint = this.config.modelUrl + modelEndPoint;

			console.log('Model End Point ' + modelEndPoint);

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

				console.log('Template End Point: ' + url);

				_parent.request.get(url);
			}

			return this; /* allow chaining */
		};

		this.getElementById = function () {
			var element = document.getElementById(this.id);

			if (element === null) {
				console.error('Element Id "' + this.id + '" Not Found.');
			} else {
				console.log('Binding To "' + this.id + '"');
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
				 * Turn on the listener to match to see if the current route is something we are listening for
				 * if a match is found then trigger the callback with the url
				 * ie. callback('/foo/bar');
				 */
				_parent.router.match();
			});

			/* Make a Request for the configuration url using the default 200 responds we just setup above */
			this.request.get(this.config.configUrl);
		}

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

		/* array of routes */
		this.routes = [];

		/* reference to intervalID */
		this.intervalID = undefined;

		/* get and normalize the current page url */
		this.getUrl = function () {
			var url = this._clearSlashes(decodeURI(location.pathname + location.search));

			url = url.replace(/\?(.*)$/, '');
			url = this._parent.config.routerRoot !== '/' ? url.replace(this._parent.config.routerRoot, '') : url;

			return this._clearSlashes(url);
		};

		/* match the router url and call the callback if a match is found */
		this.match = function (url) {

			/* do we have any routes to listen for? */
			if (this.routes.length) {

				/* did they send in a url? if not then get the current url */
				url = url || this.getUrl();

				console.log('router::match', url);

				/* loop though the routes */
				for (var key in this.routes) {
					var parameters = url.match(this.routes[key].re);

					if (parameters) {
						console.log('router::match::parameters', parameters, this.routes[key].re.toString());

						/* remove matched url  */
						parameters.shift();

						/* call the route callback and pass in the parameters */
						this.routes[key].callback.apply({}, parameters);

						break; /* break from for loop */
					}
				}
			}

			return this; /* allow chaining */
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
					re: this._normalizeRegularExpression(regularExpression),
					callback: callback
				});
			}

			/* turn on listening if it's not already */
			if (!this.intervalID) {
				this.listen();
			}

			return this; /* allow chaining */
		};

		/* remove a single route it's url regular expression */
		this.remove = function (regularExpression) {
			var re = this._normalizeRegularExpression(regularExpression);

			for (var key in this.routes) {
				if (re.toString() == this.routes[key].re.toString()) {
					this.routes.splice(key, 1);
				}
			}

			return this; /* allow chaining */
		};

		/*
		normalize the regular expression
		and convert (:any) (:num) (:hex) (:str) to actual expression values
		*/
		this._normalizeRegularExpression = function (regularExpression) {
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

			return this.stopListening(); /* allow chaining */
		};

		this.stopListening = function () {
			if (this.intervalID) {
				clearInterval(this.intervalID);
			}

			return this; /* allow chaining */
		}

		/* start router listener matching for changes in the url */
		this.listen = function () {
			/* Do we have any routes to listen for? */
			if (this.routes.length) {
				/* if we are already listening let's just make sure we stop first */
				this.stopListening();

				/* we are now listening for url changes */
				this.intervalID = setInterval(this.listener, 100, this);
			}

			return this; /* allow chaining */
		};

		/*
		the interval listener
		since interval is actually calling a function the reference to "this" doesn't work
		*/
		this.listener = function (router) {
			var url = router.getUrl();

			if (router._currentUrl != url) {

				router._currentUrl = url;

				router.match(url);
			}
		};

		/* navigate to a new url optionally specifying it as a redirect or history change */
		this.navigate = function (url, redirect) {
			url = url ? this._parent.config.routerRoot + this._clearSlashes(url) : '';
			redirect = redirect ? redirect : this._parent.config.redirect;

			console.log('router::navigate', url, redirect);

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
			return url.toString().replace(/\/$/, '').replace(/^\//, '');
		};

		/* what is our current url */
		this._currentUrl = this.getUrl();
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
			console.log('request::send', method, url, data);

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