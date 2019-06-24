/* Create the object to hold the properties and methods */
var tableSort = {
	/* Default direction */
	storageKey: '.sort',
	bound: false,
	class: 'table.table-sort',
	triggerOnSort: 'tableSort',
	dir: undefined,
	index: undefined,
	init: function() {
		if (!this.bound) {
			var parent = this;

			this.addSortIcons();

			/* handle clicks */
			$(this.class + ' thead tr th:not(.nosort)').on('click',function() {
				/* which direction are we going now? */
				parent.dir = (parent.dir === 'asc') ? 'desc' : 'asc';
				parent.index = $(parent.class + ' thead tr th').index(this) + 1;

				/* find out which column we clicked and send in the dir */
				parent.sort(parent.index,parent.dir);
			});

			this.bound = true;

			this.load();
		}
	},
	uninit: function() {
		this.bound = false;

		$('.tablesorticon').remove();
	},
	/* Do the actual sort */
	sort: function(index,dir) {
		if (index > 0) {
			console.debug('Sorting column:'+index+' direction:'+dir+' sorting:'+$(this.class + ' tbody tr').length);

			this.determineIcons(index,dir);

			if ($(this.class + ' tbody tr').length) {
				tinysort(this.class + ' tbody tr',{selector:'td:nth-child('+index+')',order:dir,data:'value'},{selector:'td:nth-child('+index+')',order:dir});

				jQuery('body').trigger(this.triggerOnSort,'sort');
			}
		}

		this.save(index,dir);
	},
	addSortIcons: function() {
		if (!$('.tablesorticon').length) {
			$(this.class + ' thead tr th:not(.nosort)').prepend('<i class="fa fa-sort tablesorticon"></i> ');
		}
	},
	determineIcons: function(index,dir) {
		/* remove all previous arrows and such */
		$(this.class + ' thead tr th i').removeClass('fa-sort-asc').removeClass('fa-sort-desc').addClass('fa-sort');

		/* remove previous highlighted column */
		$(this.class + ' thead tr th').removeClass('active');

		/* add the correct classes to the header */
		$(this.class + ' thead tr th:nth-child('+index+') i').addClass('fa-sort-'+dir).removeClass('fa-sort');

		$(this.class + ' thead tr th:nth-child('+index+')').addClass('active');
	},
	/* Load the last sort if any */
	load: function() {
		if (this.exists()) {
			var saved = storage.getItem(this.getKey(),{});

			this.index = saved.index;
			this.dir = saved.dir;

			this.sort(this.index,this.dir);
		}
	},
	/* Save the Last Sort */
	save: function(index,dir) {
		if (this.exists()) {
			storage.setItem(this.getKey(),{index:index,dir:dir});
		}
	},
	getKey: function() {
		return window.location.pathname+this.storageKey;
	},
	exists: function() {
		return $(this.class + ' thead tr th:not(.nosort)').length > 0;
	},
};

$('body').on('tiny-bind-bound',function() {
	tableSort.init();
});
$('body').on('tiny-bind-unbound',function() {
	tableSort.uninit();
});