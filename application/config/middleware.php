<?php

$middleware = [
	'/main/route404' => ['GuiMiddleware','ThemeMiddleware'],
	'/admin*' => ['AdminMiddleware','GuiMiddleware','ThemeMiddleware'],
	'/backorder*' => ['AdminMiddleware','GuiMiddleware','ThemeMiddleware'],
	'/users*' => ['PublicMiddleware','GuiMiddleware','ThemeMiddleware'],
	'/login' => ['PublicMiddleware','GuiMiddleware','ThemeMiddleware'],

	/* must be last because it's a catach all */
	'/*' => ['PublicMiddleware','GuiMiddleware'],
];