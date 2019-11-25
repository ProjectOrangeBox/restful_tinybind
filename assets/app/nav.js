/*
Setup the nav global variable for the nav "block"

1. we request tell bind what the id is
2. then where to get it's config from the server

*/
var nav = new orangeBinder.bind('nav', '/get/configuration');

nav.config.alter({
	nav: {
		open: '<div class="container"><div class="navbar-header"><button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"><span class="sr-only">Toggle</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button><a appNavigate class="navbar-brand" href="/" target="_top">O</a></div><div id="navbar" class="navbar-collapse collapse"><ul class="nav navbar-nav">',
		close: '</ul></div></div>',
		item: {
			open: '<li class="dropdown">',
			openSub: '<li class="dropdown dropdown-submenu">',
			rowSub: '<a appNavigate target="%3$s" href="%1$s" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">%2$s</a><ul class="dropdown-menu" role="menu">',
			rowSingle: '<li><a appNavigate target="%3$s" href="%1$s">%2$s</a></li>',
			hr: '<li role="separator" class="divider"></li>',
			close: '</ul></li>'
		}
	}
});

nav.templates.alter('navbar', '<nav id="nav" class="navbar navbar-inverse navbar-fixed-top" rv-html="page.nav | bootstrap_nav"></nav>');

/* for any page request use the same model and template */
nav.router.alter('(.*)', function () {
	nav.loadModel('/get/navModel', 'navbar');
});

/*
	bootstrap_nav
	Returns: converts the passed javascript object (json) into bootstrap navigation. Using app.config values for the templating
	targetL function
	return: string
	Example:
		<nav class="navbar navbar-inverse navbar-fixed-top" rv-html="page.nav | bootstrap_nav"></nav>
*/
tinybind.formatters.bootstrap_nav = function (records) {
	var html = "";

	html += nav.config.nav.open;

	var submenu = function (record, isRoot) {
		var html = "";

		if (Array.isArray(record.children)) {
			if (record.text) {
				html += isRoot ? nav.config.nav.item.open : nav.config.nav.item.openSub;
				html += sprintf(nav.config.nav.item.rowSub, record.url, record.text);

				for (var idx in record.children) {
					if (record.children[idx]) {
						html += submenu(record.children[idx], false);
					}
				}

				html += nav.config.nav.item.close;
			}
		} else {
			/* item */
			if (record.text) {
				record.target = record.target != null ? record.target : "";
				html +=
					record.text == "{hr}" ?
					nav.config.nav.item.hr :
					sprintf(
						nav.config.nav.item.rowSingle,
						record.url,
						record.text,
						record.target
					);
			}
		}

		return html;
	};

	/* start with the navbar level */
	for (var idx in records) {
		if (records[idx]) {
			html += submenu(records[idx], true);
		}
	}

	html += nav.config.nav.close;

	return html;
};