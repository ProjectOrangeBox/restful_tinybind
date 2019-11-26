	/**
	 * create the bind element
	 * arguments:
	 * id - DOM element id ie. <div id="foobar"></div>
	 * configuration url - url requested to get the "base" configuration
	 * template url - template url prefix
	 * model url - model url prefix
	 */
	class orangeBinder {
		constructor(id, configUrl, templateUrl, modelUrl) {
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
			this.page = new orangeCollection();
			this.form = new orangeCollection();
			this.user = new orangeCollection();
			this.local = new orangeCollection();

			this.config = new orangeCollection({
				settable: ['page', 'form', 'user', 'local', 'config', 'templates', 'error', 'errors', 'model'],
				gettable: ['page', 'form', 'error', 'errors', 'model'],
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

			this.triggers = new orangeCollection({
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

			this.methods = new orangeCollection();
			this.events = new orangeCollection();
			this.templates = new orangeCollection();

			this.response = new orangeResponse(this);
			this.request = new orangeRequest(this);
			this.router = new orangeRouter(this);
		}

		/**
		 * merge and replace data
		 */
		setData(data, settable) {
			settable = settable || this.config.settable;

			console.log('setData', data, settable);

			for (let index in settable) {
				let key = settable[index];

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
			this.processServerConfig(this.config);

			return this; /* allow chaining */
		}

		/**
		 * get the data about this element
		 */
		getData(gettable) {
			gettable = gettable || this.config.gettable;

			console.log('getData', gettable);

			let collection = {};

			for (let index in gettable) {
				let key = gettable[index];

				collection[key] = (typeof this[key].collect === 'function') ? this[key].collect() : this[key];
			}

			console.log(collection);

			return collection;
		}

		processServerConfig(config) {
			/* is it loaded? */
			if (storage !== undefined) {
				if (config.clearCache) {
					storage.clear();
				}

				/* if set clear older than X seconds... */
				if (config.olderThanCache !== undefined) {
					storage.removeOlderThan(config.olderThanCache);
				}
			}

			return this; /* allow chaining */
		}

		loadModel(modelEndPoint, then) {
			let orangeBind = this;

			this.response.alter(200, function (data, status, xhr) {
				orangeBind.refreshTinyBind(data, then);
			});

			/* run the query */
			this.request.get(modelEndPoint);

			return this; /* allow chaining */
		}

		loadTemplate(templateEndPoint, then) {
			let orangeBind = this;
			let cacheKey = templateEndPoint + '.template';
			let template = undefined;

			/* is this stored in our local template cache */
			if (this.templates[templateEndPoint] !== undefined) {
				/* yes it is so grab it */
				template = this.templates[templateEndPoint];
			} else if (storage !== undefined) {
				/* is this stored in our cached data */
				template = storage.getItem(cacheKey, undefined);

				console.log('getItem', cacheKey, template);
			}

			/* have we already loaded the template? */
			if (template !== undefined) {
				this.replaceElement(template);

				if (then) {
					then();
				}
			} else {
				/* setup retrieve model - success */
				this.response.alter(200, function (data, status, xhr) {
					/* if storage is setup than store a copy */
					if (storage !== undefined) {
						let cacheSeconds = data.template.cache ? data.template.cache : orangeBind.config.templateCache;

						console.log('cache key set ' + cacheKey, cacheSeconds);

						storage.setItem('setItem', cacheKey, data.template.source, cacheSeconds);
					}

					orangeBind.replaceElement(data.template.source);

					if (then) {
						then();
					}
				});

				let url = this.config.templateUrl + templateEndPoint;

				console.log('loadTemplate ' + url);

				this.request.get(url);
			}

			return this; /* allow chaining */
		}

		loadModelAndTemplate(modelEndPoint, templateEndPoint, then) {
			let orangeBind = this;

			modelEndPoint = this.config.modelUrl + modelEndPoint;

			console.log('loadModelAndTemplate ' + modelEndPoint);

			if (templateEndPoint) {
				/* load the template then the model */
				this.loadTemplate(templateEndPoint, function () {
					orangeBind.loadModel(modelEndPoint, then);
				});
			} else {
				/* just load the model */
				this.loadModel(modelEndPoint, then);
			}

			return this; /* allow chaining */
		}

		replaceElement(html) {
			this.getElementById().innerHTML = html;
		}

		getElementById() {
			let element = document.getElementById(this.id);

			if (element === null) {
				console.error('Element Id "' + this.id + '" Not Found.');
			} else {
				console.log('"' + this.id + '" bound.');
			}

			return element;
		}

		refreshTinyBind(data, then) {
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
		}

		domReady() {
			let orangeBind = this;

			if (this.config.configUrl !== '') {
				/* default init 200 callback */
				this.response.alter(200, function (data, xhr) {
					orangeBind.setData(data);

					/* send into tinybind the configuration */
					tinybind.configure(orangeBind.config.tinyBind);

					/**
					 * Turn on the listener to match to see if the current route is something we are listening for
					 * if a match is found then trigger the callback with the url
					 * ie. callback('/foo/bar');
					 */
					orangeBind.router.match();
				});

				/* Make a Request for the configuration url using the default 200 responds we just setup above */
				this.request.get(this.config.configUrl);
			} else {
				/* do not make a ajax call so we will just use the config we already have */
				tinybind.configure(this.config.tinyBind);

				/* and then fire off the router matcher */
				this.router.match();
			}
		}

	}