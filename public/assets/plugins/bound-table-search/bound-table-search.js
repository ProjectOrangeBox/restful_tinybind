/* Create the object to hold the search properties and methods */
var BoundTableSearch = {
	storageKey: '.search',
	/* Do the actual search */
	search: function() {
		var searchTerm = this.getField();

		if (typeof searchTerm === 'string') {
			if (searchTerm.length > 0) {
				/* run regular expression search on table text */

				/* hide the tr's */
				$(this.table_class).hide();

				/* wildcard - still needs to be in order of the columns */
				/* build javascript regular expression object */
				var searchReg = searchTerm.replace(/[-[\]{}()+?.,\\^$|#\s]/g, '\\$&');
				searchReg = searchReg.replace(/\*/gi,'(.*)');

				console.debug('filter regular expression ' + searchReg);

				var rex = new RegExp(searchReg,'img');

				/* filter them */
				$(this.table_class).filter(function () {
					return rex.test($(this).text().replace(/(\r\n|\n|\r)/gm," "));
				}).show(); /* show this row again */
			} else {
				/* show all */
				$(this.table_class).show();
			}

			jQuery('body').trigger('BoundTableSearch','search');

			this.save(searchTerm);
			this.determineIcons(searchTerm);
			this.updateCount(searchTerm);
		}
	},
	updateCount: function(searchTerm) {
		var vis = $(this.table_class+':visible').length;
		var all = $(this.table_class).length;
		var shown = (vis != all) ? vis + ' of ' + all : all;

		this.count_element.html(shown);

		console.debug('bound table search filtering on "'+searchTerm+'" showing ' + shown);
	},
	/* Load the search term into the input field and do the search */
	load: function() {
		this.setField(storage.getItem(window.location.pathname+this.storageKey,''));
	},
	/* Place the last search into the search box change the background color as needed */
	save: function(searchTerm) {
		storage.setItem(window.location.pathname+this.storageKey,searchTerm);
	},
	/* Set and Get the search from the search field */
	setField: function(searchTerm) {
		this.field.val(searchTerm);
	},
	getField: function() {
		return (this.field) ? this.field.val() : false;
	},
	determineIcons: function(searchTerm) {
		if (searchTerm.length > 0) {
			this.field.css({'background-color':'#F0F2F7'});
			this.field.next().addClass('text-info');
		} else {
			this.field.css({'background-color':''});
			this.field.next().removeClass('text-info');
		}
	},
	init: function() {
		var parent = this;

		/* Save a few things for "quick" access */
		this.table_class = 'table.bound-table-search tbody tr';
		this.field = $('#bound-table-search-field');
		this.count_element = $('#table-search-field-count');
		this.tbody = $('table.bound-table-search tbody');

		/* text field search with debounce */
		this.field.on('keyup',debounce(function(){
			parent.search();
		},500));

		this.load();

		this.search();

		this.bound = true;
	},
	bind: function() {
		if (!this.bound) {
			this.init();
		}

		this.search();
	},
	unbind: function() {
		this.bound = false;
	},
};

$('body').on('tiny-bind-bound',function() {
	BoundTableSearch.bind();
});
$('body').on('tiny-bind-unbound',function() {
	BoundTableSearch.unbind();
});