/* Create the object to hold the properties and methods */
var tableSort = {
	/* Default direction */
	storageKey: '.sort',
	bound: false,
	class: 'table.table-sort',
	triggerOnSort: 'tableSort',
	dir: undefined,
	index: undefined,
	init: function () {
		var parent = this;

		if (!this.bound) {
			this.addSortIcons();

			/* handle clicks */
			jQuery(this.class + ' thead tr th:not(.nosort)').on('click', function () {
				/* which direction are we going now? */
				parent.dir = (parent.dir === 'asc') ? 'desc' : 'asc';
				parent.index = jQuery(parent.class + ' thead tr th').index(this) + 1;

				/* find out which column we clicked and send in the dir */
				parent.sort(parent.index, parent.dir);

				console.log('click');
			});

			this.bound = true;

			this.load();

			this.sort(this.index, this.dir);
		}
	},
	uninit: function () {
		this.removeSortIcons();

		jQuery(this.class + ' thead tr th:not(.nosort)').off('click');

		this.bound = false;
	},
	/* Do the actual sort */
	sort: function (index, dir) {
		if (index > 0) {
			console.debug('Sorting column:' + index + ' direction:' + dir + ' sorting:' + jQuery(this.class + ' tbody tr').length);

			this.determineIcons(index, dir);

			if (jQuery(this.class + ' tbody tr').length) {
				tinysort(this.class + ' tbody tr', {
					selector: 'td:nth-child(' + index + ')',
					order: dir,
					data: 'value'
				}, {
					selector: 'td:nth-child(' + index + ')',
					order: dir
				});

				jQuery('body').trigger(this.triggerOnSort, 'sort');
			}
		}

		this.save(index, dir);
	},
	addSortIcons: function () {
		if (!jQuery('.tablesorticon').length) {
			jQuery(this.class + ' thead tr th:not(.nosort)').prepend('<i class="fa fa-sort tablesorticon"></i> ');
		}
	},
	removeSortIcons: function () {
		jQuery('.tablesorticon').remove();
	},
	determineIcons: function (index, dir) {
		/* remove all previous arrows and such */
		jQuery(this.class + ' thead tr th i').removeClass('fa-sort-asc').removeClass('fa-sort-desc').addClass('fa-sort');

		/* remove previous highlighted column */
		jQuery(this.class + ' thead tr th').removeClass('active');

		/* add the correct classes to the header */
		jQuery(this.class + ' thead tr th:nth-child(' + index + ') i').addClass('fa-sort-' + dir).removeClass('fa-sort');

		jQuery(this.class + ' thead tr th:nth-child(' + index + ')').addClass('active');
	},
	/* Load the last sort if any */
	load: function () {
		if (this.exists()) {
			var saved = storage.getItem(this.getKey(), {});

			this.index = saved.index;
			this.dir = saved.dir;
		}
	},
	/* Save the Last Sort */
	save: function (index, dir) {
		if (this.exists()) {
			storage.setItem(this.getKey(), {
				index: index,
				dir: dir
			});
		}
	},
	getKey: function () {
		return window.location.pathname + this.storageKey;
	},
	exists: function () {
		return jQuery(this.class + ' thead tr th:not(.nosort)').length > 0;
	},
};

jQuery('body').on('tiny-bind-bound', function () {
	tableSort.init();
});

jQuery('body').on('tiny-bind-unbound', function () {
	tableSort.uninit();
});