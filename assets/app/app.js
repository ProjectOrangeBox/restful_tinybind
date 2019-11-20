/* Setup the Application */

app.config
	.alter('url', '/get/configuration') /* the url to call to get the applications configuration from the server */
	.alter('layoutUrl', '/get/layout') /* the url to call to get a layout ie. /get/layout/{name} */
	.alter('default', {
		Precision: 2,
		ThousandSeparator: ',',
		DecimalSeparator: '.',
		DateFormat: 'YYYY-MM-DD',
		TimeFormat: 'HH:mm:ss',
		DatetimeFormat: 'YYYY-MM-DD HH:mm:ss'
	})
	.alter('nav', {
		open: '<div class="container"><div class="navbar-header"><button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"><span class="sr-only">Toggle</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button><a appNavigate class="navbar-brand" href="/" target="_top">O</a></div><div id="navbar" class="navbar-collapse collapse"><ul class="nav navbar-nav">',
		close: '</ul></div></div>',
		item: {
			open: '<li class="dropdown">',
			openSub: '<li class="dropdown dropdown-submenu">',
			rowSub: '<a appNavigate target="%3$s" href="%1$s" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">%2$s</a><ul class="dropdown-menu" role="menu">',
			rowSingle: '<li><a appNavigate target="%3$s" href="%1$s">%2$s</a></li>',
			hr: '<li role="separator" class="divider"></li>',
			close: '</ul></li>',
		},
	});

/* Add Routes */
app.router
	.alter('multi/edit/(:num)', function (primary_id) {
		app.loadModel('/multi/edit/' + primary_id, app.config.layoutUrl + '/multi/details');
	})
	.alter('multi/create', function () {
		app.loadModel('/multi/create', app.config.layoutUrl + '/multi/details');
	})
	.alter('multi', function () {
		app.loadModel('/multi/index', app.config.layoutUrl + '/multi/index');
	})
	.alter('people/edit/(:num)', function (primary_id) {
		app.loadModel('/people/edit/' + primary_id, app.config.layoutUrl + '/people/details');
	})
	.alter('people/create', function () {
		app.loadModel('/people/create', app.config.layoutUrl + '/people/details');
	})
	.alter('people', function () {
		app.loadModel('/people/index', app.config.layoutUrl + '/people/index');
	})
	.alter('zipcodes/edit/(:num)', function (primary_id) {
		app.loadModel('/zipcodes/edit/' + primary_id, app.config.layoutUrl + '/zipcodes/details');
	})
	.alter('zipcodes/create', function () {
		app.loadModel('/zipcodes/create', app.config.layoutUrl + '/zipcodes/details');
	})
	.alter('zipcodes', function () {
		app.loadModel('/zipcodes/index', app.config.layoutUrl + '/zipcodes/index');
	})
	.alter('catalog/edit/(:num)', function (primary_id) {
		app.loadModel('/catalog/edit/' + primary_id, app.config.layoutUrl + '/catalog/details');
	})
	.alter('catalog/create', function () {
		app.loadModel('/catalog/create', app.config.layoutUrl + '/catalog/details');
	})
	.alter('catalog', function () {
		app.loadModel('/catalog/index', app.config.layoutUrl + '/catalog/index');
	})
	.alter('robot/edit/(:num)', function (primary_id) {
		app.loadModel('/robot/edit/' + primary_id, app.config.layoutUrl + '/robot/details');
	})
	.alter('robot/create', function () {
		app.loadModel('/robot/create', app.config.layoutUrl + '/robot/details');
	})
	.alter('robot', function () {
		app.loadModel('/robot/index', app.config.layoutUrl + '/robot/index');
	})
	/* mpa example page - when this page loads load this model */
	.alter('food/edit/(:num)', function (primary_id) {
		app.loadModel('/food/edit/' + primary_id);
	})
	/* mpa example page - when this page loads load this model */
	.alter('food/create', function () {
		app.loadModel('/food/create');
	})
	/* mpa example page - when this page loads load this model */
	.alter('food', function () {
		app.loadModel('/food/index');
	})
	.alter(function () {
		/* leave empty to allow for standard web page loads */
		/* default route */
		/* redirect */
		console.log('default: ' + app.router.getUrl());
		app.refresh();
	});

app.response.alter(404, function (xhr, status, error) {
	/* don't show the default alert() - instead show not found */
	app.loadTemplate(app.config.layoutUrl + '/notfound');
});

app.method
	.alter('buildUrl', function (args) {
		var that = args.pop();
		var event = args.pop();

		event.preventDefault();

		return sprintf.apply(args[0], args);
	})
	.alter('submit', function (redirect) {
		/* created record - create */
		app.response.alter(201, function (data, status, xhr) {
			/* good redirect */
			notify.removeAll();

			app.router.navigate(app.page.path, redirect);
		});

		/* accepted record - update */
		app.response.alter(202, function (data, status, xhr) {
			/* good redirect" */
			notify.removeAll();

			app.router.navigate(app.page.path, redirect);
		});

		/* not accepted - show errors */
		app.response.alter(406, function (xhr, status, error) {
			/* good show errors */
			app.setData(xhr.responseJSON);

			if (app.error) {
				notify.removeAll();
				for (var key in app.errors) {
					for (var key2 in app.errors[key]) {
						console.log(app.errors[key][key2]);
						notify.error(app.errors[key][key2]);
					}
				}
			}
		});

		app.request[app.form.method](app.form.action, app.getData());
	});

/* Button Events */
app.event
	.alter('create', function (url, event) {
		event.preventDefault();
		app.router.navigate(url + '/create');
	})
	.alter('edit', function (url, primaryId, event) {
		event.preventDefault();
		app.router.navigate(url + '/edit/' + primaryId);
	})
	.alter('navigate', function () {
		/* spa navigate - to the first argument passed */
		app.router.navigate(app.method.buildUrl([].slice.call(arguments)), false);
	})
	.alter('redirect', function () {
		/* mpa redirect - to the first argument passed */
		app.router.navigate(app.method.buildUrl([].slice.call(arguments)), true);
	})
	.alter('delete', function (url, primaryId, event) {
		event.preventDefault();
		/* we need to save this for the 202 responds */
		app.local.closest_tr = jQuery(this).closest('tr');

		/**
		 * if result is true then they pressed ok
		 * if result is false then they pressed cancel
		 */
		bootbox.confirm({
			message: 'Are you sure you want to delete this record?',
			buttons: {
				cancel: {
					label: '<i class="fa fa-times"></i> Cancel',
				},
				confirm: {
					label: '<i class="fa fa-trash"></i> Delete',
					className: 'btn-danger',
				}
			},
			callback: function (confirm) {
				if (confirm) {
					/* accepted record - delete */
					app.response.change(202, function (data, status, xhr) {
						app.local.closest_tr.remove();
					});

					app.request.delete(url + '/delete/' + primaryId);
				}
			},
		});
	})
	.alter('submit', function (event) {
		event.preventDefault();
		app.method.submit(false);
	})
	.alter('submitRedirect', function (event) {
		event.preventDefault();
		app.method.submit(true);
	});