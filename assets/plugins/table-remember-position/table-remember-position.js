/**
 *
 * Save the Windows Scroll Location
 *
 */
$(window).scroll(function () {
	storage.setItem(window.location.pathname + '.scroll_pos', $(window).scrollTop());
});

$('body').on('orange::bind-bind', function () {
	var ypos = storage.getItem(window.location.pathname + '.scroll_pos', null);

	if (ypos > 0) {
		$(window).scrollTop(ypos);
	}
});