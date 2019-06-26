
tinybind.config = {
	defaultPrecision: 2,
	defaultThousandSeparator: "'",
	defaultDecimalSeparator: ".",

	defaultDateFormat: "YYYY-MM-DD",
	defaultTimeFormat: "HH:mm:ss",
	defaultDatetimeFormat: "YYYY-MM-DD HH:mm:ss",
	nav: {
		open: '<div class="container"><div class="navbar-header"><button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"><span class="sr-only">Toggle</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button><a appNavigate class="navbar-brand" href="/" target="_top">O</a></div><div id="navbar" class="navbar-collapse collapse"><ul class="nav navbar-nav">',
		close: '</ul></div></div>',
		item: {
			open: '<li class="dropdown">',
			openSub: '<li class="dropdown dropdown-submenu">',
			rowSub: '<a appNavigate target="%3$s" href="%1$s" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">%2$s</a><ul class="dropdown-menu" role="menu">',
			rowSingle: '<li><a appNavigate target="%3$s" href="%1$s">%2$s</a></li>',
			hr: '<li role="separator" class="divider"></li>',
			close: '</ul></li>',
		},
	},
};
