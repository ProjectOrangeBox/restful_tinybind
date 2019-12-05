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
	debug: false,
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
 * <a class="btn btn-default btn-sm js-esc" rv-on-click="events.navigate | args page.path"><i	class="fa fa-share fa-flip-horizontal" aria-hidden="true"></i> Go Back</a>
 */
app.events.alter({
	/*
	create & edit could have simply used events.navigate
	but this allow us a little more control and unified naming
	*/

	/* <a rv-on-click="events.create | args page.path">create</a> */
	create: function (url, event) {
		event.preventDefault();

		app.router.navigate(url + '/create');
	},
	/* <a rv-on-click="events.edit | args page.path record.id">edit</a> */
	edit: function (url, primaryId, event) {
		event.preventDefault();

		app.router.navigate(url + '/edit/' + primaryId);
	},
	/* <a rv-on-click="events.delete | args page.path record.id">delete</a> */
	delete: function (url, primaryId, event) {
		event.preventDefault();

		app.method.deleteRow(url, primaryId, this);
	},
	/*
	just switch the URL

	<a rv-on-click="events.navigate | args '%s/goto/%s' page.path page.method">navigate</a>
	*/
	navigate: function () {
		/* convert the arguments to an array */
		var args = Array.from(arguments);

		/* blast off the extra stuff attached */
		args = args.slice(0, -2);

		/* get the sprintf format string */
		var format = args.shift();

		app.router.navigate(vsprintf(format, args), false);
	},
	/*
	preform an actual http redirect

	<a rv-on-click="events.navigate | args '%s/goto/%s' page.path page.method">redirect</a>
	*/
	redirect: function () {
		/* convert the arguments to an array */
		var args = Array.from(arguments);

		/* blast off the extra stuff attached */
		args = args.slice(0, -2);

		/* get the sprintf format string */
		var format = args.shift();

		/* using the format send what's left in */
		app.router.navigate(vsprintf(format, args), true);
	},
	/* go to somewhere based on html data attrubutes */
	dataAttrExample: function (element) {
		/*
		<a class="btn btn-default btn-sm js-esc" data-format="%s/foobar" rv-data-path="page.path" rv-on-click="events.goto">
			<i class="fa fa-share fa-flip-horizontal" aria-hidden="true"></i> Go Back
		</a>
		*/
		var url = sprintf(app.methods.getData(element, 'format'), app.methods.getData(element, 'path'));

		app.router.navigate(url, false);
	},
	/* submit the model but don't redirect */
	submit: function (event) {
		event.preventDefault();

		app.methods.submit(false);
	},
	/* submit the model and redirect */
	submitRedirect: function (event) {
		event.preventDefault();

		app.methods.submit(true);
	}
});

/* A "safe" place to attach reuseable application methods */
app.methods.alter({
	/* get a data attribute from a DOM element */
	'getData': function (element, name, defaultValue) {
		defaultValue = defaultValue || '';

		var value = element.target.getAttribute('data-' + name);

		return (value == null) ? defaultValue : value;
	},
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