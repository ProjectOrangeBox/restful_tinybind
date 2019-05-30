/* Create the object to hold the properties and methods */
var tableSort = {
	/* Default direction */
	dir: 'desc',
	index: 0,
	iconsAdded: false,
	/* Do the actual sort */
	sort: function(index,dir) {
		if (index > 0) {
			console.debug('Sorting column:'+index+' direction:'+dir+' sorting:'+$('table.table-sort tbody tr').length);

			this.determineIcons(index,dir);

			if ($('table.table-sort tbody tr').length) {
				tinysort('table.table-sort tbody tr',{selector:'td:nth-child('+index+')',order:dir,data:'value'},{selector:'td:nth-child('+index+')',order:dir});

				jQuery('body').trigger('tableSort','sort');
			}
		}

		this.save(index,dir);
	},
	addSortIcons: function() {
		if (!this.iconsAdded) {
			$('table.table-sort thead tr th:not(.nosort)').prepend('<i class="fa fa-sort"></i> ');
			this.iconsAdded = true;
		}
	},
	determineIcons: function(index,dir) {
		/* remove all previous arrows and such */
		$('table.table-sort thead tr th i').removeClass('fa-sort-asc').removeClass('fa-sort-desc').addClass('fa-sort');

		/* remove previous highlighted column */
		$('table.table-sort thead tr th').removeClass('active');

		/* add the correct classes to the header */
		$('table.table-sort thead tr th:nth-child('+index+') i').addClass('fa-sort-'+dir).removeClass('fa-sort');

		$('table.table-sort thead tr th:nth-child('+index+')').addClass('active');
	},
	/* Load the last sort if any */
	load: function() {
		var saved = storage.getItem(window.location.pathname+'.ssc','');

		if (saved != '') {
			parts = saved.split("\t");

			this.index = parts[0];
			this.dir = parts[1];
		}

		this.sort(this.index,this.dir);
	},
	/* Save the Last Sort */
	save: function(index,dir) {
		storage.setItem(window.location.pathname+'.ssc',index + "\t" + dir);
	},
	init: function() {
		var parent = this;

		this.addSortIcons();

		/* handle clicks */
		$('table.table-sort thead tr th:not(.nosort)').on('click',function() {
			/* which direction are we going now? */
			parent.dir = (parent.dir == 'asc') ? 'desc' : 'asc';
			parent.index = $('table.table-sort thead tr th').index(this) + 1;

			/* find out which column we clicked and send in the dir */
			parent.sort(parent.index,parent.dir);
		});

		this.bound = true;
	}
};

trigger.register('bound',function(event,isbound) {
	if (isbound) {
		if (!tableSort.bound) {
			tableSort.init();
		}

		tableSort.load();
	} else {
		tableSort.bound = undefined;
		tableSort.iconsAdded = false;
	}
});