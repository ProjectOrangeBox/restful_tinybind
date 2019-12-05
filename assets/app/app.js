/*
Setup the global variable app
this is attached to the DOM element with the id of the first parameter
the "base" configuration is loaded from the second parameter
prefix all template request with the third parameter
prefix all models request with the fourth parameter
*/
var app = new orangeBinder('app', '/ServerConfiguration', '/Template');

/* setup the application defaults */
app.config.alter({
	debug: true,
	defaults: {
		Precision: 2,
		ThousandSeparator: ',',
		DecimalSeparator: '.',
		DateFormat: 'YYYY-MM-DD',
		TimeFormat: 'HH:mm:ss',
		DatetimeFormat: 'YYYY-MM-DD HH:mm:ss'
	}
});

/* setup the application routes */
app.router.alter({
	'multi/edit/(:num)': function (primary_id) {
		app.load.block('/multi/details', '/multi/edit/' + primary_id);
	},
	'multi/create': function () {
		app.load.block('/multi/details', '/multi/create');
	},
	'multi/edit/(:num)': function (primary_id) {
		app.load.block('/multi/edit/' + primary_id, '/multi/details');
	},
	'multi/create': function () {
		app.load.block('/multi/details', '/multi/create');
	},
	'multi': function () {
		app.load.block('/multi/index', '/multi/index');
	},
	'people/edit/(:num)': function (primary_id) {
		app.load.block('/people/details', '/people/edit/' + primary_id);
	},
	'people/create': function () {
		app.load.block('/people/details', '/people/create');
	},
	'people': function () {
		app.load.block('/people/index', '/people/index');
	},
	'zipcodes/edit/(:num)': function (primary_id) {
		app.load.block('/zipcodes/details', '/zipcodes/edit/' + primary_id);
	},
	'zipcodes/create': function () {
		app.load.block('/zipcodes/details', '/zipcodes/create');
	},
	'zipcodes': function () {
		app.load.block('/zipcodes/index', '/zipcodes/index');
	},
	'catalog/edit/(:num)': function (primary_id) {
		app.load.block('/catalog/details', '/catalog/edit/' + primary_id);
	},
	'catalog/create': function () {
		app.load.block('/catalog/details', '/catalog/create');
	},
	'catalog': function () {
		app.load.block('/catalog/index', '/catalog/index');
	},
	'robot/edit/(:num)': function (primary_id) {
		app.load.block('/robot/details', '/robot/edit/' + primary_id);
	},
	'robot/create': function () {
		app.load.block('/robot/details', '/robot/create');
	},
	'robot': function () {
		app.load.block('/robot/index', '/robot/index');
	},

	/*
	full page refresh example pages
	there is no template load because the entire page is included in the refresh
	so when this page loads load this model
	*/
	'food/edit/(:num)': function (primary_id) {
		app.load.model('/food/edit/' + primary_id);
	},

	'food/create': function () {
		app.load.model('/food/create');
	},

	'food': function () {
		app.load.model('/food/index');
	},

	/* default route / action */
	'(.*)': function () {}
});

/**
 * TinyBind Events
 *
 * <a class="btn btn-default btn-sm js-esc" rv-on-click="events.navigate | wrap page.path"><i	class="fa fa-share fa-flip-horizontal" aria-hidden="true"></i> Go Back</a>
 */
app.events.alter({
	create: function (url, event) {
		event.preventDefault();

		app.router.navigate(url + '/create');
	},
	edit: function (url, primaryId, event) {
		event.preventDefault();

		app.router.navigate(url + '/edit/' + primaryId);
	},
	navigate: function () {
		/* single page application navigate - to the first argument passed */
		app.router.navigate(app.methods.buildUrl([].slice.call(arguments)), false);
	},

	/*
	<a class="btn btn-default btn-sm js-esc" rv-path="page.path" rv-on-click="events.inspect">
		<i class="fa fa-share fa-flip-horizontal" aria-hidden="true"></i> Go Back
	</a>
	*/
	inspect: function (element, rootObject) {
		/* get an attribute from a DOM element */
		console.log(element.target.getAttribute('path'));

		/* root Orange Bind Element */
		console.log(rootObject);
	},
	redirect: function () {
		/* multi page application redirect - to the first argument passed */
		app.router.navigate(app.methods.buildUrl([].slice.call(arguments)), true);
	},
	delete: function (url, primaryId, event) {
		event.preventDefault();

		app.method.deleteRow(url, primaryId, this);
	},
	submit: function (event) {
		event.preventDefault();

		app.methods.submit(false);
	},
	submitRedirect: function (event) {
		event.preventDefault();

		app.methods.submit(true);
	}
});

//app.request.on(404, function (xhr, status, error) {
/* don't show the default alert() - instead show not found */
//app.load.template('/notfound');
//});

/* A "safe" place to attach reuseable application methods */
app.methods.alter({
	'deleteRow': function (url, primaryId, that) {
		/* we need to save this for the 202 responds */
		app.local.closest_tr = jQuery(that).closest('tr');

		/**
		 * if result is true then they pressed ok
		 * if result is false then they pressed cancel
		 */
		bootbox.confirm({
			/* build the dialog */
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
			/* add the button callbacks */
			callback: function (confirm) {
				/* confirm = true */
				if (confirm) {
					/* setup the 202 (accepted) responds */
					app.request.change(202, function (data, status, xhr) {
						/* delete row */
						app.local.closest_tr.remove();
					});

					/* send the delete request */
					app.request.delete(url + '/delete/' + primaryId);
				}
			},
		});
	},
	/* let url = app.methods.buildUrl('/foo/%s/bar','123'); */
	'buildUrl': function (args) {
		let that = args.pop();
		let event = args.pop();

		event.preventDefault();

		return sprintf.apply(args[0], args);
	},
	/**
	 * Submit Button
	 * Use the action and method in the <form...>
	 * To determine the url and method
	 *
	 */
	'submit': function (redirect, method, action, data) {
		method = method || app.form.method;
		action = action || app.form.action;
		data = data || app.get();

		/* created record - create */
		app.request.on(201, function (data, status, xhr) {
			/* good redirect */
			notify.removeAll();

			app.router.navigate(app.page.path, redirect);
		});

		/* accepted record - update */
		app.request.on(202, function (data, status, xhr) {
			/* good redirect" */
			notify.removeAll();

			app.router.navigate(app.page.path, redirect);
		});

		/* not accepted - show errors */
		app.request.on(406, function (xhr, status, error) {
			/* good show errors */
			app.set(xhr.responseJSON);

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

		app.request[method](action, data);
	}
});