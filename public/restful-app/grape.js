/*
 * http://krasimirtsonev.com/blog/article/A-modern-JavaScript-router-in-100-lines-history-api-pushState-hash-url
 */
var router = {
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
}

/**
 *
 * https://blikblum.github.io/tinybind/
 * https://github.com/matthieuriolo/rivetsjs-stdlib
 *
 */

/**
 * Setup the default application
 */
var app = {
	id: '#app',
	local: {}, /* storage for local application variables */
	error: false, /* do we have an error - boolean true/false */
	errors: {}, /* "errors":{"robots":{"Name":"Name is required.","Year":"Year is required."}}} */
	record: {}, /* single record */
	records: [{}], /* array of single records */
	page: {}, /* page variables */
	form: {}, /* form variables */
	_layouts: [], /* layout cache */
	bound: undefined, /* are we attached to the DOM */
	init() {
		app.helpers.route();
	},
	events: {
	},
	addEvent(name,handler)  {
		app.events[name] = handler;
		return this;
	},
	helpers: {
		defaultResponse: {
			200: function(data, textStatus, jqXHR){ console.log(data, textStatus, jqXHR); alert('200 (ok) handler'); },
			201: function(data, textStatus, jqXHR){ console.log(data, textStatus, jqXHR); alert('201 (created) handler'); },
			202: function(data, textStatus, jqXHR){ console.log(data, textStatus, jqXHR); alert('202 (accepted) handler'); },
			401: function(jqXHR, textStatus, errorThrown){ console.log(jqXHR, textStatus, errorThrown); alert('401 (unauthorized) handler'); },
			404: function(jqXHR, textStatus, errorThrown){ console.log(jqXHR, textStatus, errorThrown); alert('404 (not found) handler'); },
			406: function(jqXHR, textStatus, errorThrown){ console.log(jqXHR, textStatus, errorThrown); alert('406 (not accepted) handler'); },
			409: function(jqXHR, textStatus, errorThrown){ console.log(jqXHR, textStatus, errorThrown); alert('409 (conflict) handler'); },
			500: function(jqXHR, textStatus, errorThrown){ console.log(jqXHR, textStatus, errorThrown); alert('500 (server error) handler'); },
		},
		ajax(method,url,data,handlers) {
			jQuery.ajax({
				method: method,
				url: url,
				data: data,
				dataType: 'json',
				cache: false,
				timeout: 5000, /* 5 seconds */
				async: true,
				statusCode: Object.assign(app.helpers.defaultResponse,handlers),
			});
		},
		getHandlers() {
			return app.helpers.defaultResponse;
		},
		setData(data) {
			/* overwrite */
			var params = ['error','errors','record','records'];

			for (var index in params) {
				app[params[index]] = data[params[index]];
			}

			/* merge */
			var params = ['page','form'];

			for (var index in params) {
				if (data[params[index]]) {
					for (key2 in data[params[index]]) {
						app[params[index]][key2] = data[params[index]][key2];
					}
				}
			}
		},
		getData() {
			return {
				error: app.error,
				errors: app.errors,
				record: app.record,
				records: app.records,
				page: app.page,
				form: app.form,
			};
		},
		load(layout,modelEndPoint) {
			/* ok */
			app.helpers.defaultResponse[200] = app.helpers.default200;

			if (app._layouts[layout]) {
				/* we already cached the layout so just bind */
				jQuery('body').trigger('unbound');
				jQuery(app.id).html(app._layouts[layout]);

				app.helpers.ajax('get',modelEndPoint,{},app.helpers.getHandlers());
			} else {
				/* get the layout & bind */
				jQuery.get(layout,function(data) {
					app._layouts[layout] = data;

					jQuery('body').trigger('unbound');
					jQuery(app.id).html(app._layouts[layout]);

					app.helpers.ajax('get',modelEndPoint,{},app.helpers.getHandlers());
				});
			}
		},
		/* ok */
		default200(data, textStatus, jqXHR) {
			app.helpers.setData(data);

			app.bound = tinybind.bind(document.querySelector(app.id),app);

			jQuery('body').trigger('bound');
		},
		/* created */
		form201(data, textStatus, jqXHR) {
			app.helpers.load('index','/rest/indexModel');
		},
		/* accepted */
		form202(data, textStatus, jqXHR) {
			app.helpers.load('index','/rest/indexModel');
		},
		/* not acceptable */
		form406(jqXHR, textStatus, errorThrown) {
			app = Object.assign(app,jqXHR.responseJSON);

			if (app.error) {
				notify.removeAll();
				for (const key in app.errors) {
					for (const key2 in app.errors[key]) {
						notify.addError(app.errors[key][key2]);
					}
				}
			}
		},
		route() {
			if (!router._routerSetup) {
				router.config({ mode:'history'});
				router._routerSetup = true;
				router.listen();
			}

			router.check(window.location.pathname);
		},
	}
};

/* bootstrap */
document.addEventListener('DOMContentLoaded',function(){
	app.init();
});