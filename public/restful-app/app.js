/* Add Routes */
router
	.add(/rest\/create/, function(primary_id) {
		app.helpers.load('/rest/layoutDetails','/rest/createModel');
	})
	.add(/rest\/edit\/(.*)/, function(primary_id) {
		app.helpers.load('/rest/layoutDetails','/rest/editModel/'+primary_id);
	})
	.add(/rest/, function() {
		notify.removeAll();
		app.helpers.load('/rest/layoutIndex','/rest/indexModel');
	})
	.add(function() {
		console.log('> default');
	});

/* Add Events */
app
	.addEvent('create',function(url, event) {
		event.preventDefault();

		router.navigate(app.page.path + '/create');
	})
	.addEvent('edit',function(url, primaryId, event) {
		event.preventDefault();

		router.navigate(app.page.path + '/edit/' + primaryId);
	})
	.addEvent('delete',function(url, primaryId, event) {
		event.preventDefault();

		/* we need to save this for the 202 responds */
		app.local.closest_tr = jQuery(this).closest('tr');

		/**
		 * if result is true then they pressed ok
		 * if result is false then they pressed cancel
		 */
		bootbox.confirm('Are you sure?',function(okButton) {
			if (okButton) {
				/* accepted record - delete */
				app.helpers.defaultResponse[202] = function(data, textStatus, jqXHR) {
					app.local.closest_tr.remove();
				};

				app.helpers.ajax('delete',app.page.path + '/delete/' + primaryId,{},app.helpers.getHandlers());
			}
		});
	})
	.addEvent('goback',function(url, event) {
		event.preventDefault();

		router.navigate(app.page.path);
	})
	.addEvent('submit',function(event) {
		event.preventDefault();

		/* created record - create */
		app.helpers.defaultResponse[201] = function(data, textStatus, jqXHR) {
			/* good redirect */
			router.navigate(app.page.path);
		}

		/* accepted record - update */
		app.helpers.defaultResponse[202] = function(data, textStatus, jqXHR) {
			/* good redirect */
			router.navigate(app.page.path);
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
