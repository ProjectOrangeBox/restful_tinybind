/**
 *
 * Save the Windows Scroll Location
 *
 */
$(window).scroll(function() {
	$.jStorage.set(window.location.href+'scrollTop',$(window).scrollTop());
});

$('body').on('bound',function(event,isbound) {
	if (isbound) {
		var ypos = $.jStorage.get(window.location.href+'scrollTop',null);

		if (ypos > 0) {
			$(window).scrollTop(ypos);
		}
	}
});