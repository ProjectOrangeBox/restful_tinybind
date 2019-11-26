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

			/* setup config with defaults */
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

			/* customer trigger storage */
			this.triggers = new orangeCollection();

			/* user methods storage */
			this.methods = new orangeCollection();

			/**
			 * user storage for events
			 * <button class="btn btn-default" type="submit" rv-on-click="events.submit">Submit</button>
			 */
			this.events = new orangeCollection();

			/* user storage for templates */
			this.templates = new orangeCollection();

			/* send ajax requests to the server */
			this.request = new orangeRequest(this);

			/* Handle the changes of the browser URL  */
			this.router = new orangeRouter(this);

			this.load = new orangeLoader(this);

			var parent = this;

			document.addEventListener("DOMContentLoaded", function (e) {
				parent._DOMContentLoaded(parent);
			});
		}

		/**
		 * handle simple triggers
		 *
		 * - listener
		 * jQuery('body').on('foobar', function(event, arg1, arg2) {
		 * 	alert( arg1 + "\n" + arg2 );
		 * });
		 *
		 */
		trigger(msg, args) {
			jQuery('body').trigger(msg, args);
		}

		/**
		 * Called once the DOM is ready
		 *
		 * private
		 *
		 */
		_DOMContentLoaded(orangeBind) {
			/* Setup TinyBind */
			tinybind.configure(orangeBind.config.tinyBind);

			if (orangeBind.config.configUrl !== '') {
				/* default init 200 callback */
				orangeBind.request.on(200, function (data, xhr) {
					orangeBind.set(data);
					orangeBind.router.match();
				}).get(orangeBind.config.configUrl);
			} else {
				orangeBind.router.match();
			}
		}

		/**
		 * merge and replace data
		 */
		set(data, settable) {
			settable = settable || this.config.settable;

			console.log('set', data, settable);

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
			/* is it loaded? */
			if (storage !== undefined) {
				if (this.config.clearCache) {
					storage.clear();
				}

				/* if set clear older than X seconds... */
				if (this.config.olderThanCache !== undefined) {
					storage.removeOlderThan(this.config.olderThanCache);
				}
			}

			return this; /* allow chaining */
		}

		/**
		 * get the data about this element
		 */
		get(gettable) {
			gettable = gettable || this.config.gettable;

			console.log('get', gettable);

			let collection = {};

			for (let index in gettable) {
				let key = gettable[index];

				collection[key] = (typeof this[key].collect === 'function') ? this[key].collect() : this[key];
			}

			console.log(collection);

			return collection;
		}

		replace(html) {
			this.element().innerHTML = html;
		}

		element() {
			let element = document.getElementById(this.id);

			if (element === null) {
				console.error('Element Id "' + this.id + '" Not Found.');
			} else {
				console.log('"' + this.id + '" bound.');
			}

			return element;
		}

		rebind(data, then) {
			this.trigger('tiny-bind-unbound', [data, then]);

			/* unbind tinybind */
			if (this.bound) {
				this.bound.unbind();
			}

			/* update instance data */
			if (data) {
				this.set(data);
			}

			this.bound = tinybind.bind(this.element(), this);

			/* tell everyone we now have new data */
			this.trigger('tiny-bind-bound', [data, then]);

			if (then) {
				then();
			}
		}

	}