var orangeBinder = {
	bind: function (id, configUrl, templateUrl, modelUrl) {
		this.id = id;

		this.error = false;
		this.errors = {};

		this.model = {};
		this.record = {};
		this.records = {};

		this.page = {};
		this.form = {};
		this.local = {};

		this.bound = undefined;

		this.user = new orangeBinder.collection(this);
		this.local = new orangeBinder.collection(this);
		this.config = new orangeBinder.collection(this);
		this.methods = new orangeBinder.collection(this);
		this.events = new orangeBinder.collection(this);
		this.triggers = new orangeBinder.collection(this);

		this.response = new orangeBinder.response(this);
		this.request = new orangeBinder.request(this);
		this.router = new orangeBinder.router(this);

		this.config.alter({
			defaults: {},
			configUrl: configUrl,
			modelUrl: modelUrl,
			templateUrl: templateUrl,
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

		this.setData = function (data) {
			parent = this;

			console.info("setData", data);

			/* overwrite */
			["error", "errors", "model"].forEach(function (element) {
				if (data[element]) {
					parent[element] = data[element];
				}
			});

			/* deep merge these values */
			["page", "form", "config"].forEach(function (element) {
				if (data[element]) {
					parent[element] = jQuery.extend(true, parent[element], data[element]);
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
		};

		this.getData = function () {
			return {
				error: this.error,
				errors: this.errors,
				model: this.model,
				page: this.page,
				form: this.form
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
			var key = templateEndPoint + ".template";

			/* Get bind template from browser local session storage? */
			var template = storage.getItem(key, undefined);

			/* have we already loaded the template? */
			if (template) {
				document.getElementById(this.id).innerHTML = template;

				if (then) {
					then();
				}
			} else {
				/* setup retrieve model - success */
				this.response.alter(200, function (data, status, xhr) {
					var cacheSeconds = data.template.cache ?
						data.template.cache :
						_parent.config.templateCache;

					storage.setItem(key, data.template.source, cacheSeconds);

					document.getElementById(_parent.id).innerHTML = data.template.source;

					if (then) {
						then();
					}
				});

				_parent.request.get(templateEndPoint);
			}

			return this; /* allow chaining */
		};

		this.refresh = function (data, then) {
			this.trigger.unbound();

			/* unbind tinybind */
			if (this.bound) {
				this.bound.unbind();
			}

			/* update app data */
			if (data) {
				this.setData(data);
			}

			/* rebind */
			this.bound = tinybind.bind(document.getElementById(this.id), app);

			/* tell everyone we now have new data */
			this.trigger.bound();

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

		/* finish init */

		/* attach our default triggers */
		this.triggers.alter({
			bound: function () {
				jQuery('body').trigger('tiny-bind-bound');
			},
			unbound: function () {
				jQuery('body').trigger('tiny-bind-unbound');
			}
		});

		/* attach out default route */
		this.router.alter('', function () {
			/* leave empty to allow for standard web page loads */
			/* default route */
			/* redirect */
			console.log('default route');
			app.refresh();
		});

		this.domReady = function () {
			var _parent = this;

			/* default init 200 callback */
			this.response.alter(200, function (data, xhr) {
				/**
				 * try to application (app) variables
				 * replace: error, errors, model
				 * merge: page, form, config
				 */
				_parent.setData(data);

				/**
				 * send into tinybind the configuration
				 */
				tinybind.configure(_parent.config.tinyBind);

				/**
				 * check to see if the current route is something we are listening for
				 * if a match is found then trigger the callback with the url
				 * ie. callback('/foo/bar');
				 */
				_parent.router.check();
			});

			/* Make a Request for the configuration url using the default 200 responds we just setup above */
			this.request.get(this.config.configUrl);
		}
	},
	router: function (parent) {
		this._parent = parent;

		this.routes = [];
		this.interval = undefined;
		this.listening = undefined;

		this.check = function (url) {
			/**
			 * Do we have any routes to listen for?
			 */
			if (this.routes.length) {
				/* turn on listening */

				/* did they send in a url? if not then get the current url */
				url = url || this.getUrl();

				console.info("router::check", url);

				/* are we listening for changes? if not start listener */
				if (!this.listening) {
					this.listening = this.listen();
				}

				/* check for a match */
				for (var i = 0; i < this.routes.length; i++) {
					var match = url.match(this.routes[i].re);

					if (match) {
						match.shift();

						console.info("router::check::match", this.routes[i].re.toString());

						/* call the route callback */
						this.routes[i].callback.apply({}, match);

						break; /* break from for loop */
					}
				}
			}

			return this; /* allow chaining */
		};

		this.getUrl = function () {
			var url = "";

			url = this._clearSlashes(decodeURI(location.pathname + location.search));
			url = url.replace(/\?(.*)$/, "");
			url =
				app.config.routerRoot !== "/" ?
				url.replace(app.config.routerRoot, "") :
				url;

			return this._clearSlashes(url);
		};

		this.alter = function (regularExpression, callback) {
			if (typeof regularExpression === "object") {
				for (var property in regularExpression) {
					this.alter(property, regularExpression[property]);
				}
			} else {
				/* trim / fore & aft */
				regularExpression = this._clearSlashes(regularExpression);

				/* escape / to \/ */
				regularExpression = regularExpression.replace(
					new RegExp("/", "g"),
					"\\/"
				);

				/* add CodeIgniter matches */
				regularExpression = regularExpression.replace(
					new RegExp(":any", "g"),
					"[^/]+"
				); /* anything */
				regularExpression = regularExpression.replace(
					new RegExp(":num", "g"),
					"[0-9]+"
				); /* number only */
				regularExpression = regularExpression.replace(
					new RegExp(":hex", "g"),
					"[0-9a-f]+"
				); /* hex values */
				regularExpression = regularExpression.replace(
					new RegExp(":str", "g"),
					"[0-9a-zA-Z]+"
				); /* str values */

				/* add to the routes array */
				this.routes.push({
					re: new RegExp(regularExpression),
					callback: callback
				});
			}

			return this; /* allow chaining */
		};

		this.remove = function (param) {
			var _parent = this;

			this.routes.forEach(function (value, index) {
				if (
					value.callback === param ||
					value.re.toString() === param.toString()
				) {
					_parent.routes.splice(index, 1);
				}
			});

			return this; /* allow chaining */
		};

		this.flush = function () {
			this.routes = [];

			return this; /* allow chaining */
		};

		this.listen = function () {
			var _parent = this;
			var current = this.getUrl();

			/* Do we have any routes to listen for? */
			if (this.routes.length) {
				clearInterval(this.interval);

				/* we are now listening for url changes */
				this.interval = setInterval(function () {
					if (current !== _parent.getUrl()) {
						current = _parent.getUrl();
						_parent.check(current);
					}
				}, 50);
			}

			return this; /* allow chaining */
		};

		this.navigate = function (url, redirect) {
			url = url ? app.config.routerRoot + this._clearSlashes(url) : "";
			redirect = redirect ? redirect : app.config.redirect;

			console.info("router::navigate", url, redirect);

			if (redirect) {
				window.location.href = url;
			} else {
				history.pushState(null, null, url);
			}

			return this; /* allow chaining */
		};

		this._clearSlashes = function (url) {
			return url
				.toString()
				.replace(/\/$/, "")
				.replace(/^\//, "");
		};
	},
	response: function (parent) {
		this._parent = parent;

		this.callbacks = {
			/* standard get layout or get model */
			200: function (data, status, xhr) {
				console.log(arguments);
				alert("200 (ok) callback");
			},
			/* success on create */
			201: function (data, status, xhr) {
				console.log(arguments);
				alert("201 (created) callback");
			},
			/* success on edit */
			202: function (data, status, xhr) {
				console.log(arguments);
				alert("202 (accepted) callback");
			},
			/* access to resource not allowed */
			401: function (xhr, status, error) {
				console.log(arguments);
				alert("401 (unauthorized) callback");
			},
			/* resource not found */
			404: function (xhr, status, error) {
				console.log(arguments);
				alert("404 (not found) callback");
			},
			/* error submitting resource (create, edit, delete) */
			406: function (xhr, status, error) {
				console.log(arguments);
				alert("406 (not accepted) callback");
			},
			/* resource conflict ie. trying to create a new resource with the same primary id */
			409: function (xhr, status, error) {
				console.log(arguments);
				alert("409 (conflict) callback");
			},
			/* internal server error */
			500: function (xhr, status, error) {
				console.log(arguments);
				alert("500 (server error) callback");
			}
		};

		this.alter = function (code, callback) {
			if (typeof code === "object") {
				for (var property in code) {
					this.alter(property, code[property]);
				}
			} else if (Number.isInteger(code) && typeof callback === "function") {
				/* change the responds callback based on the returned http status code */
				this.callbacks[code] = callback;
			}

			return this;
		};
	},
	request: function (parent) {
		this._parent = parent;

		/* any method */
		this.send = function (method, url, data, callbacks) {
			console.info("request::send", method, url, data);

			jQuery.ajax({
				method: method,
				url: url,
				data: data,
				dataType: "json",
				cache: !app.config.ajaxCacheBuster,
				/* ajax cache buster? */
				async: true,
				/* always! */
				timeout: app.config.ajaxTimeout,
				/* 5 seconds */
				statusCode: app.response.alter(callbacks).callbacks
			});

			return this;
		};

		/* REST / HTTP - get */
		this.get = function (url, data, callbacks) {
			return this.send("get", url, data, callbacks);
		};

		/* REST / HTTP  - post */
		this.post = function (url, data, callbacks) {
			return this.send("post", url, data, callbacks);
		};

		/* REST / HTTP  - patch */
		this.patch = function (url, data, callbacks) {
			return this.send("patch", url, data, callbacks);
		};

		/* CRUD / SQL / REST / HTTP  - delete */
		this.delete = function (url, data, callbacks) {
			return this.send("delete", url, data, callbacks);
		};

		/* CRUD - create */
		this.create = function (url, data, callbacks) {
			return this.send("post", url, data, callbacks);
		};

		/* CRUD - read */
		this.read = function (url, data, callbacks) {
			return this.send("get", url, data, callbacks);
		};

		/* CRUD / SQL - update */
		this.update = function (url, data, callbacks) {
			return this.send("patch", url, data, callbacks);
		};

		/* SQL - insert */
		this.insert = function (url, data, callbacks) {
			return this.send("post", url, data, callbacks);
		};
	},
	collection: function (parent) {
		this._parent = parent;

		this.alter = function (name, value) {
			if (typeof name === "object") {
				for (var property in name) {
					this.alter(property, name[property]);
				}
			} else {
				this[name] = value;
			}

			return this;
		};

		this.collect = function () {
			var collection = {};

			for (var propertyName in this) {
				if (
					typeof this[propertyName] !== "function" &&
					propertyName !== "_parent"
				) {
					collection[propertyName] = this[propertyName];
				}
			}

			return collection;
		};
	}
};