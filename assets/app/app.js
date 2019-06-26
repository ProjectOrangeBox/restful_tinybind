/* where to request configuration from */
app.config.url = '/get/configuration';
app.config.layoutUrl = '/get/layout';

/* Add Routes */
app.router
	.add('multi/edit/(:num)', function(primary_id) {
		app.loadModel('/multi/edit/' + primary_id,app.config.layoutUrl + '/multi/details');
	})
	.add('multi/create', function() {
		app.loadModel('/multi/create',app.config.layoutUrl + '/multi/details');
	})
	.add('multi', function() {
		app.loadModel('/multi/index',app.config.layoutUrl + '/multi/index');
	})
	.add('people/edit/(:num)', function(primary_id) {
		app.loadModel('/people/edit/' + primary_id,app.config.layoutUrl + '/people/details');
	})
	.add('people/create', function() {
		app.loadModel('/people/create',app.config.layoutUrl + '/people/details');
	})
	.add('people', function() {
		app.loadModel('/people/index',app.config.layoutUrl + '/people/index');
	})
	.add('zipcodes/edit/(:num)', function(primary_id) {
		app.loadModel('/zipcodes/edit/' + primary_id,app.config.layoutUrl + '/zipcodes/details');
	})
	.add('zipcodes/create', function() {
		app.loadModel('/zipcodes/create',app.config.layoutUrl + '/zipcodes/details');
	})
	.add('zipcodes', function() {
		app.loadModel('/zipcodes/index',app.config.layoutUrl + '/zipcodes/index');
	})
	.add('catalog/edit/(:num)', function(primary_id) {
		app.loadModel('/catalog/edit/' + primary_id,app.config.layoutUrl + '/catalog/details');
	})
	.add('catalog/create', function() {
		app.loadModel('/catalog/create',app.config.layoutUrl + '/catalog/details');
	})
	.add('catalog', function() {
		app.loadModel('/catalog/index',app.config.layoutUrl + '/catalog/index');
	})
	.add('robot/edit/(:num)', function(primary_id) {
		app.loadModel('/robot/edit/' + primary_id,app.config.layoutUrl + '/robot/details');
	})
	.add('robot/create', function() {
		app.loadModel('/robot/create',app.config.layoutUrl + '/robot/details');
	})
	.add('robot', function() {
		app.loadModel('/robot/index',app.config.layoutUrl + '/robot/index');
	})
	/* mpa example page - when this page loads load this model */
	.add('food/edit/(:num)', function(primary_id) {
		app.loadModel('/food/edit/' + primary_id);
	})
	/* mpa example page - when this page loads load this model */
	.add('food/create', function() {
		app.loadModel('/food/create');
	})
	/* mpa example page - when this page loads load this model */
	.add('food', function() {
		app.loadModel('/food/index');
	})
	.add(function() {
		/* leave empty to allow for standard web page loads */
		/* default route */
		/* redirect */
		console.log('default: ' + app.router.getUrl());
		app.refresh();
	});

app.response.change(404,function(xhr,status,error) {
	/* don't show the default alert() - instead show not found */
	app.loadTemplate(app.config.layoutUrl + '/notfound');
});

app.userMethods = {
	buildUrl: function(args) {
		var that = args.pop();
		var event = args.pop();

		event.preventDefault();

		return sprintf.apply(args[0],args);
	},
	submit: function(redirect) {
		/* created record - create */
		app.response.change(201,function(data,status,xhr) {
			/* good redirect */
			notify.removeAll();

			app.router.navigate(app.page.path,redirect);
		});

		/* accepted record - update */
		app.response.change(202,function(data,status,xhr) {
			/* good redirect" */
			notify.removeAll();

			app.router.navigate(app.page.path,redirect);
		});

		/* not accepted - show errors */
		app.response.change(406,function(xhr,status,error) {
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

		app.request[app.form.method](app.form.action,app.getData());
	}
}

/* Button Events */
app.event
	.add('create',function(url, event) {
		event.preventDefault();
		app.router.navigate(url + '/create');
	})
	.add('edit',function(url, primaryId, event) {
		event.preventDefault();
		app.router.navigate(url + '/edit/' + primaryId);
	})
	.add('navigate',function() {
		/* spa navigate */
		app.router.navigate(app.userMethods.buildUrl([].slice.call(arguments)),false);
	})
	.add('redirect',function() {
		/* mpa redirect */
		app.router.navigate(app.userMethods.buildUrl([].slice.call(arguments)),true);
	})
	.add('delete',function(url, primaryId, event) {
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
			callback: function(confirm) {
				if (confirm) {
					/* accepted record - delete */
					app.response.change(202,function(data,status,xhr) {
						app.local.closest_tr.remove();
					});

					app.request.delete(url + '/delete/' + primaryId);
				}
			},
		});
	})
	.add('submit',function(event) {
		event.preventDefault();
		app.user.submit(false);
	})
	.add('submitRedirect',function(event) {
		event.preventDefault();
		app.user.submit(true);
	});
