/* Add Routes */
app.router
	.add(/catalog\/edit\/(.*)/, function(primary_id) {
		app.helpers.load('/catalog/layoutDetails','/catalog/editModel/' + primary_id);
	})
	.add(/catalog\/create/, function() {
		app.helpers.load('/catalog/layoutDetails','/catalog/createModel');
	})
	.add(/catalog/, function(primary_id) {
		app.helpers.load('/catalog/layoutIndex','/catalog/indexModel');
	})
	.add(/edit\/(.*)/, function(primary_id) {
		app.helpers.load('/rest/layoutDetails','/rest/editModel/' + primary_id);
	})
	.add(/create/, function(primary_id) {
		app.helpers.load('/rest/layoutDetails','/rest/createModel');
	})
	.add(function() {
		notify.removeAll();
		app.helpers.load('/rest/layoutIndex','/rest/indexModel');
	});

/* Add Events */
app.event
	.add('goto',function(url, event) {
		event.preventDefault();
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
					callback: function() {
						/* accepted record - delete */
						app.helpers.defaultResponse[202] = function(data, textStatus, jqXHR) {
							app.local.closest_tr.remove();
						};

						app.helpers.ajax('delete',url + '/delete/' + primaryId,{},app.helpers.getHandlers());
					}
        }
    	},
		});
	})
	.add('submit',function(event) {
		event.preventDefault();

		/* created record - create */
		app.helpers.defaultResponse[201] = function(data, textStatus, jqXHR) {
			/* good redirect */
			app.router.navigate(app.page.path);
		}

		/* accepted record - update */
		app.helpers.defaultResponse[202] = function(data, textStatus, jqXHR) {
			/* good redirect" */
			app.router.navigate(app.page.path);
		}

		/* not accepted - show errors */
		app.helpers.defaultResponse[406] = function(jqXHR, textStatus, errorThrown) {
			/* good show errors */
			app.helpers.setData(jqXHR.responseJSON);

			if (app.error) {
				notify.removeAll();
				for (const key in app.errors) {
					for (const key2 in app.errors[key]) {
						notify.addError(app.errors[key][key2]);
					}
				}
			}
		}

		app.helpers.ajax(app.form.method,app.form.action,app.helpers.getData(),app.helpers.getHandlers());
	});
