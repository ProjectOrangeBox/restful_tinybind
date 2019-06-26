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

	return function() {
		var context = this, args = arguments;
		var later = function() {
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

$(document).on('click','[appNavigate]',function(event){
	event.preventDefault();

	var href = $(this).attr('href');
	var redirect = ($(this).attr('target') == '_top');

	if (href) {
		app.router.navigate(href,redirect);
	}
});

$(document).on('tiny-bind-bound',function() {
	$('select').selectpicker();
});