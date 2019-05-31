/* where to request configuration from */
app.configurationURL = '/layout/configuration';

/* Add Routes */
app.router
	.add(/catalog\/edit\/(.*)/, function(primary_id) {
		app.helpers.load('/layout/get/catalog/details','/catalog/edit/' + primary_id);
	})
	.add(/catalog\/create/, function() {
		app.helpers.load('/layout/get/catalog/details','/catalog/create');
	})
	.add(/catalog/, function(primary_id) {
		app.helpers.load('/layout/get/catalog/index','/catalog/index');
	})
	.add(/edit\/(.*)/, function(primary_id) {
		app.helpers.load('/layout/get/robot/details','/robot/edit/' + primary_id);
	})
	.add(/create/, function(primary_id) {
		app.helpers.load('/layout/get/robot/details','/robot/create');
	})
	.add(function() {
		/* default */
		app.helpers.load('/layout/get/robot/index','/robot/index');
	});

app.response[404] = function(xhr,status,error) {
	/* don't show the default alert() - instead show not found */
	app.helpers.load('/layout/get/notfound');
}

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
					app.response[202] = function(data,status,xhr) {
						app.local.closest_tr.remove();
					};

					app.request('delete',url + '/delete/' + primaryId);
				}
			},
		});
	})
	.add('submit',function(event) {
		event.preventDefault();

		/* created record - create */
		app.response[201] = function(data,status,xhr) {
			/* good redirect */
			app.router.navigate(app.page.path);
		}

		/* accepted record - update */
		app.response[202] = function(data,status,xhr) {
			/* good redirect" */
			app.router.navigate(app.page.path);
		}

		/* not accepted - show errors */
		app.response[406] = function(xhr,status,error) {
			/* good show errors */
			app.helpers.setData(xhr.responseJSON);

			if (app.error) {
				notify.removeAll();
				for (const key in app.errors) {
					for (const key2 in app.errors[key]) {
						notify.error(app.errors[key][key2]);
					}
				}
			}
		}

		app.request(app.form.method,app.form.action,app.helpers.getData());
	});
