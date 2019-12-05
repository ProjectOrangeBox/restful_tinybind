/**
 *
 * example:
 * text field search with debounce
 *
 * table_search_field.field.on('keyup',debounce(function(){
 *   table_search_field.search(table_search_field.get_field());
 * },500));
 *
 */

function debounce(func, wait, immediate) {
	var timeout;

	return function () {
		var context = this,
			args = arguments;
		var later = function () {
			timeout = null;
			if (!immediate) {
				func.apply(context, args);
			}
		};

		var callNow = immediate && !timeout;

		clearTimeout(timeout);

		timeout = setTimeout(later, wait);

		if (callNow) {
			func.apply(context, args);
		}
	};
}

/* when the router navgates to something new */
$(document).on('orange::router-navgate', function (event) {
	notify.removeAll();
});

/* navbar menu item clicks */
$(document).on('click', '[appNavigate]', function (event) {
	event.preventDefault();

	var href = $(this).attr('href');
	var redirect = ($(this).attr('target') == '_top');

	if (href) {
		nav.router.navigate(href, redirect);
	}
});

/* when we bind then refresh the pickers */
$(document).on('orange::bind-bind', function () {
	$('select').selectpicker();
});