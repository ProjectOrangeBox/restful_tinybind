/* Create the object to hold the search properties and methods */
var BoundTableSearch = {
	storageKey: '.search',
	bound: false,
	fieldSelector: '#bound-table-search-field',
	tableSelector: 'table.bound-table-search',
	countSelector: '#table-search-field-count',
	highlightColor: '#F0F2F7',
	highlightIcon: 'text-info',
	triggerOnSearch: 'BoundTableSearch',
	element: {},
	init: function () {
		if (!this.bound) {
			var parent = this;

			/* Save DOM elements for "quick" access */
			this.element.table = this.tableSelector + ' tbody tr';
			this.element.field = $(this.fieldSelector);

			/* register text field search with debounce */
			this.element.field.on('keyup', debounce(function () {
				parent.search();
			}, 500));

			/* load anything saved into the search field */
			this.load();

			/* do search */
			this.search();

			this.bound = true;
		}
	},
	uninit: function () {
		this.bound = false;
	},
	/* Do the actual search */
	search: function () {
		var searchTerm = this.getField();

		if (typeof searchTerm === 'string') {
			if (searchTerm.length > 0) {
				/* run regular expression search on table text */

				/* hide all the tr's */
				$(this.element.table).hide();

				/* wildcard - still needs to be in order of the columns */

				/* build javascript regular expression object */
				var searchReg = searchTerm.replace(/[-[\]{}()+?.,\\^$|#\s]/g, '\\$&');

				searchReg = searchReg.replace(/\*/gi, '(.*)');

				var rex = new RegExp(searchReg, 'i');

				//console.log('filter regular expression ' + searchReg, rex);

				/* filter them */
				$(this.element.table).filter(function (index) {
					var rowString = $(this).text();

					rowString = rowString.replace(/(\r\n|\n|\r)/gm, ' ');
					rowString = rowString.replace(/\s\s+/g, ' ');
					rowString = rowString.replace(/[\x00-\x1F\x7F-\x9F]/g, '');
					rowString = rowString.trim();

					var bool = rex.test(rowString);

					//console.log(rex, rowString, bool);

					return bool;
				}).show(); /* show this row again */
			} else {
				/* nothing to filter on so show all */
				$(this.element.table).show();
			}

			jQuery('body').trigger(this.triggerOnSearch, searchTerm);

			this.save(searchTerm);
			this.determineIcons(searchTerm);
			this.updateCount(searchTerm);
		}
	},
	updateCount: function (searchTerm) {
		var vis = $(this.element.table + ':visible').length;
		var all = $(this.element.table).length;
		var shown = (vis !== all) ? vis + ' of ' + all : all;

		$(this.countSelector).html(shown);

		console.log('bound table search filtering on "' + searchTerm + '" showing ' + shown);
	},
	/* Load the search term into the input field and do the search */
	load: function () {
		var data = storage.getItem(window.location.pathname + this.storageKey, {});

		if (typeof data.search === 'string') {
			this.setField(data.search);
		}
	},
	/* Place the last search into the search box change the background color as needed */
	save: function (search) {
		storage.setItem(window.location.pathname + this.storageKey, {
			search: search
		});
	},
	/* Set and Get the search from the search field */
	setField: function (searchTerm) {
		this.element.field.val(searchTerm);
	},
	getField: function () {
		return (this.element.field) ? this.element.field.val() : false;
	},
	determineIcons: function (searchTerm) {
		if (searchTerm.length > 0) {
			this.element.field.css({
				'background-color': this.highlightColor
			});
			this.element.field.next().addClass(this.highlightIcon);
		} else {
			this.element.field.css({
				'background-color': ''
			});
			this.element.field.next().removeClass(this.highlightIcon);
		}
	},
};

$('body').on('orange::bind-bind', function () {
	BoundTableSearch.init();
});
$('body').on('orange::bind-unbind', function () {
	BoundTableSearch.uninit();
});