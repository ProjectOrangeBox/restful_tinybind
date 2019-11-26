/*
Setup the nav global variable for the nav "block"

1. we request tell bind what the id is
2. then where to get it's config from the server

*/
var nav = new orangeBinder('nav');

nav.config.alter({
	nav: {
		open: '<div class="container"><div class="navbar-header"><button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"><span class="sr-only">Toggle</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button><a appNavigate class="navbar-brand" href="/" target="_top">O</a></div><div id="navbar" class="navbar-collapse collapse"><ul class="nav navbar-nav">',
		close: "</ul></div></div>",
		item: {
			open: '<li class="dropdown">',
			openSub: '<li class="dropdown dropdown-submenu">',
			rowSub: '<a appNavigate target="%3$s" href="%1$s" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">%2$s</a><ul class="dropdown-menu" role="menu">',
			rowSingle: '<li><a appNavigate target="%3$s" href="%1$s">%2$s</a></li>',
			hr: '<li role="separator" class="divider"></li>',
			close: "</ul></li>"
		}
	}
});

/* for any page request use the same model and template */
nav.router.alter("(.*)", function () {
	nav.loadModel("/get/navModel", function () {
		nav.methods.updateBootstrapNav();
	});
});

nav.methods.updateBootstrapNav = function () {
	let html = nav.config.nav.open;

	/* start with the navbar level */
	for (let idx in nav.model) {
		if (nav.model[idx]) {
			html += nav.methods.bootstrap_nav_submenu(nav.model[idx], true);
		}
	}

	document.getElementById(nav.id).innerHTML = html + nav.config.nav.close;
}

nav.methods.bootstrap_nav_submenu = function (record, isRoot) {
	let html = "";

	if (Array.isArray(record.children)) {
		if (record.text) {
			html += isRoot ? nav.config.nav.item.open : nav.config.nav.item.openSub;
			html += sprintf(nav.config.nav.item.rowSub, record.url, record.text);

			for (var idx in record.children) {
				if (record.children[idx]) {
					html += nav.methods.bootstrap_nav_submenu(record.children[idx], false);
				}
			}

			html += nav.config.nav.item.close;
		}
	} else {
		/* item */
		if (record.text) {
			record.target = record.target != null ? record.target : "";
			html += record.text == "{hr}" ? nav.config.nav.item.hr : sprintf(nav.config.nav.item.rowSingle, record.url, record.text, record.target);
		}
	}

	return html;
}