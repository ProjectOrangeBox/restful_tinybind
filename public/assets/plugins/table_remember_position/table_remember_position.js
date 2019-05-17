/**
 *
 * Save the Windows Scroll Location
 *
 */
$(window).scroll(function() {
	storage.setItem(window.location.pathname+'.st',$(window).scrollTop());
});

$('body').on('bound',function(event,isbound) {
	if (isbound) {
		var ypos = storage.getItem(window.location.pathname+'.st',null);

		if (ypos > 0) {
			$(window).scrollTop(ypos);
		}
	}
});