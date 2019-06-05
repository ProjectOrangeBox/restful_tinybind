/* where to request configuration from */
app.config.url = '/get/configuration';
app.config.layoutUrl = '/get/layout';

/* Add Routes */
app.router
	.add(/people\/edit\/(.*)/, function(primary_id) {
		app.loadModel('/people/edit/' + primary_id,app.config.layoutUrl + '/people/details');
	})
	.add(/people\/create/, function() {
		app.loadModel('/people/create',app.config.layoutUrl + '/people/details');
	})
	.add(/people/, function() {
		app.loadModel('/people/index',app.config.layoutUrl + '/people/index');
	})
	.add(/catalog\/edit\/(.*)/, function(primary_id) {
		app.loadModel('/catalog/edit/' + primary_id,app.config.layoutUrl + '/catalog/details');
	})
	.add(/catalog\/create/, function() {
		app.loadModel('/catalog/create',app.config.layoutUrl + '/catalog/details');
	})
	.add(/catalog/, function() {
		app.loadModel('/catalog/index',app.config.layoutUrl + '/catalog/index');
	})
	.add(/robot\/edit\/(.*)/, function(primary_id) {
		app.loadModel('/robot/edit/' + primary_id,app.config.layoutUrl + '/robot/details');
	})
	.add(/robot\/create/, function() {
		app.loadModel('/robot/create',app.config.layoutUrl + '/robot/details');
	})
	.add(function() {
		/* default */
		app.loadModel('/robot/index',app.config.layoutUrl + '/robot/index');
	});

app.response.change(404,function(xhr,status,error) {
	/* don't show the default alert() - instead show not found */
	app.loadTemplate(app.config.layoutUrl + '/notfound');
});

/* Button Events */
app.event
	.add('goto',function(url, event) {
		event.preventDefault();
		notify.removeAll();
		app.router.navigate(url);
	})
	.add('create',function(url, event) {
		event.preventDefault();
		app.router.navigate(url + '/create');
	})
	.add('edit',function(url, primaryId, event) {
		event.preventDefault();
		app.router.navigate(url + '/edit/' + primaryId);
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

		/* created record - create */
		app.response.change(201,function(data,status,xhr) {
			/* good redirect */
			notify.removeAll();
			app.router.navigate(app.page.path);
		});

		/* accepted record - update */
		app.response.change(202,function(data,status,xhr) {
			/* good redirect" */
			notify.removeAll();
			app.router.navigate(app.page.path);
		});

		/* not accepted - show errors */
		app.response.change(406,function(xhr,status,error) {
			/* good show errors */
			app.setData(xhr.responseJSON);

			if (app.error) {
				notify.removeAll();
				for (const key in app.errors) {
					for (const key2 in app.errors[key]) {
						notify.error(app.errors[key][key2]);
					}
				}
			}
		});

		app.request[app.form.method](app.form.action,app.getData());
	});
