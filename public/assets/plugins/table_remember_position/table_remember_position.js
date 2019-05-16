/**
 *
 * Save the Windows Scroll Location
 *
 */
$(window).scroll(function() {
	storage.set(window.location.href+'scrollTop',$(window).scrollTop());
});

$('body').on('bound',function(event,isbound) {
	if (isbound) {
		var ypos = storage.get(window.location.href+'scrollTop',null);

		if (ypos > 0) {
			$(window).scrollTop(ypos);
		}
	}
});